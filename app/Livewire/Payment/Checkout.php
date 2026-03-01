<?php

namespace App\Livewire\Payment;

use App\Models\Course;
use App\Models\InstitutionCourseAccess;
use App\Models\Payment;
use Livewire\Component;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Checkout extends Component
{
    public $course;
    public float $effectivePrice = 0;
    public $paymentMethod = 'mobile_money'; // mobile_money ou card
    public $processing = false;
    public $errorMessage = '';

    public function mount($courseId)
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $this->course = Course::with('creator')->findOrFail($courseId);

        if ($user->institution_id && $this->course->is_paid && (float) $this->course->price > 0) {
            $institutionAccess = InstitutionCourseAccess::where('institution_id', $user->institution_id)
                ->where('course_id', $this->course->id)
                ->first();

            if (! $institutionAccess || ! $institutionAccess->is_enabled) {
                return redirect()->route('courses.show', $this->course->id)
                    ->with('error', 'Ce cours payant n\'est pas encore autorisé pour votre institution.');
            }
        }

        $this->effectivePrice = (float) ($this->course->getPriceForUser($user) ?? 0);

        // Vérifier que le cours est payant
        if (!$this->course->is_paid || $this->effectivePrice <= 0) {
            return redirect()->route('courses.show', $this->course->id)
                ->with('error', 'Ce cours est gratuit.');
        }

        // Vérifier si l'utilisateur est déjà inscrit
        if ($user->enrollments()->where('course_id', $this->course->id)->exists()) {
            return redirect()->route('courses.show', $this->course->id)
                ->with('info', 'Vous êtes déjà inscrit à ce cours.');
        }

        // Configuration de Fedapay
        FedaPay::setApiKey(config('services.fedapay.secret_key'));
        FedaPay::setEnvironment(config('services.fedapay.environment'));
    }

    public function initiatePayment()
    {
        $this->processing = true;
        $this->errorMessage = '';

        try {
            // Créer un enregistrement de paiement
            $paymentReference = 'PAY-' . strtoupper(Str::random(12));
            
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'course_id' => $this->course->id,
                'amount' => $this->effectivePrice,
                'transaction_id' => $paymentReference,
                'payment_method' => $this->paymentMethod,
                'status' => 'pending',
            ]);

            // Créer une transaction Fedapay
            $transaction = Transaction::create([
                'description' => "Inscription au cours: {$this->course->title}",
                'amount' => $this->effectivePrice,
                'currency' => [
                    'iso' => 'XOF'
                ],
                'callback_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
                'custom_metadata' => [
                    'payment_reference' => $paymentReference,
                    'course_id' => $this->course->id,
                    'user_id' => Auth::id(),
                    'user_email' => Auth::user()?->email,
                    'user_name' => Auth::user()?->name,
                ],
                'customer' => [
                    'firstname' => Auth::user()?->name,
                    'lastname' => '',
                    'email' => Auth::user()?->email,
                    'phone_number' => [
                        'number' => Auth::user()?->phone ?? '',
                        'country' => 'BJ' // Benin par défaut, à adapter selon votre pays
                    ]
                ]
            ]);

            // Générer le token de paiement
            $token = $transaction->generateToken();

            // Mettre à jour le transaction_id avec l'ID Fedapay
            $payment->update(['transaction_id' => $transaction->id]);

            // Rediriger vers la page de paiement Fedapay
            return redirect($token->url);

        } catch (\Exception $e) {
            $this->processing = false;
            $this->errorMessage = "Erreur lors de l'initialisation du paiement: " . $e->getMessage();
            
            Log::error('Erreur Fedapay checkout:', [
                'message' => $e->getMessage(),
                'course_id' => $this->course->id,
                'user_id' => Auth::id()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.payment.checkout')
            ->layout('components.layouts.app');
    }
}
