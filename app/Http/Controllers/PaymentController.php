<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use FedaPay\FedaPay;
use FedaPay\Transaction;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Configuration de Fedapay
        FedaPay::setApiKey(config('services.fedapay.secret_key'));
        FedaPay::setEnvironment(config('services.fedapay.environment'));
    }

    /**
     * Webhook de confirmation de paiement depuis Fedapay
     */
    public function webhook(Request $request)
    {
        try {
            // Récupérer la signature du webhook
            $signature = $request->header('X-FedaPay-Signature');
            $webhookSecret = config('services.fedapay.webhook_secret');

            // Vérifier la signature du webhook (sécurité)
            $payload = $request->getContent();
            $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

            if ($signature !== $expectedSignature) {
                Log::warning('Fedapay webhook signature invalide');
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $data = $request->all();
            $event = $data['event'] ?? null;

            // Traiter uniquement les événements de transaction approuvée
            if ($event === 'transaction.approved') {
                $transactionData = $data['entity'] ?? [];
                $transactionId = $transactionData['id'] ?? null;
                $customMetadata = $transactionData['custom_metadata'] ?? [];

                if ($transactionId) {
                    // Récupérer les détails de la transaction depuis Fedapay
                    $transaction = Transaction::retrieve($transactionId);

                    // Récupérer le paiement dans la base de données
                    $payment = Payment::where('transaction_id', $transactionId)
                        ->orWhere('transaction_id', $customMetadata['payment_reference'] ?? '')
                        ->first();

                    if ($payment && $payment->status !== 'completed') {
                        // Mettre à jour le statut du paiement
                        $payment->update([
                            'status' => 'completed',
                            'transaction_id' => $transactionId,
                            'payment_method' => $transactionData['payment_method'] ?? 'fedapay',
                        ]);

                        // Créer l'inscription au cours
                        Enrollment::firstOrCreate([
                            'user_id' => $payment->user_id,
                            'course_id' => $payment->course_id,
                        ], [
                            'enrolled_at' => now(),
                        ]);

                        Log::info("Paiement confirmé: {$transactionId} pour le cours {$payment->course_id}");
                    }
                }
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Erreur webhook Fedapay: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Page de succès après paiement
     */
    public function success(Request $request)
    {
        $transactionId = $request->query('transaction_id');
        
        if ($transactionId) {
            $payment = Payment::where('transaction_id', $transactionId)->first();
            
            if ($payment) {
                return view('payment.success', compact('payment'));
            }
        }

        return redirect()->route('dashboard')->with('success', 'Paiement effectué avec succès !');
    }

    /**
     * Page d'annulation/échec de paiement
     */
    public function cancel(Request $request)
    {
        $transactionId = $request->query('transaction_id');
        
        if ($transactionId) {
            $payment = Payment::where('transaction_id', $transactionId)->first();
            
            if ($payment && $payment->status === 'pending') {
                $payment->update(['status' => 'cancelled']);
            }
        }

        return redirect()->route('dashboard')->with('error', 'Le paiement a été annulé.');
    }
}
