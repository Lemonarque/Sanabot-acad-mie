import './bootstrap';

const initTinyMce = () => {
    if (!window.tinymce) return;
    const editors = document.querySelectorAll('[data-tinymce]');
    editors.forEach((el) => {
        if (el.dataset.tinymceInit) return;

        const inputId = el.dataset.input;
        const input = inputId ? document.getElementById(inputId) : null;
        if (!input) return;

        if (!el.id) {
            el.id = `tinymce-${Math.random().toString(36).slice(2)}`;
        }

        window.tinymce.init({
            target: el,
            height: 360,
            menubar: false,
            branding: false,
            plugins: [
                'lists', 'link', 'image', 'media', 'table', 'code', 'help', 'wordcount',
            ],
            toolbar: [
                'undo redo | blocks | bold italic underline forecolor backcolor |',
                'alignleft aligncenter alignright alignjustify | bullist numlist |',
                'link image media table | removeformat | code',
            ].join(' '),
            setup(editor) {
                editor.on('init', () => {
                    const initial = input.value || '';
                    editor.setContent(initial);
                });
                editor.on('change keyup', () => {
                    const content = editor.getContent();
                    input.value = content;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                });
            },
        });

        el.dataset.tinymceInit = 'true';
    });
};

const initPlyr = () => {
    if (!window.Plyr) return;
    document.querySelectorAll('.js-player').forEach((el) => {
        if (el.dataset.plyrInit) return;
        const player = new window.Plyr(el, {
            ratio: '16:9',
            controls: [
                'play-large',
                'play',
                'progress',
                'current-time',
                'mute',
                'volume',
                'settings',
                'fullscreen',
            ],
        });
        el.dataset.plyrInit = 'true';
        el.__plyr = player;
    });
};

const initAll = () => {
    initTinyMce();
    initPlyr();
};

document.addEventListener('livewire:navigated', initAll);
document.addEventListener('livewire:initialized', () => {
    initAll();
    if (window.Livewire && window.Livewire.hook) {
        window.Livewire.hook('message.processed', () => {
            initTinyMce();
            document.querySelectorAll('[data-tinymce]').forEach((el) => {
                const inputId = el.dataset.input;
                const input = inputId ? document.getElementById(inputId) : null;
                if (!input || !el.id) return;
                const editor = window.tinymce.get(el.id);
                if (!editor) return;
                const current = editor.getContent();
                if (current !== input.value) {
                    editor.setContent(input.value || '');
                }
            });
            initPlyr();
        });
    }
});
