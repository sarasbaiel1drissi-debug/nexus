(function () {
    if (window.bootstrap && window.bootstrap.Modal) {
        return;
    }

    const instances = new WeakMap();

    class Modal {
        constructor(element) {
            this.element = element;
            this.backdrop = null;
        }

        static getOrCreateInstance(element) {
            if (!instances.has(element)) {
                instances.set(element, new Modal(element));
            }

            return instances.get(element);
        }

        show() {
            if (!this.element) {
                return;
            }

            this.element.classList.add('show');
            this.element.style.display = 'block';
            this.element.removeAttribute('aria-hidden');
            this.element.setAttribute('aria-modal', 'true');
            document.body.classList.add('modal-open');

            if (!document.querySelector('.modal-backdrop')) {
                this.backdrop = document.createElement('div');
                this.backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(this.backdrop);
            }
        }

        hide() {
            if (!this.element) {
                return;
            }

            this.element.classList.remove('show');
            this.element.style.display = 'none';
            this.element.setAttribute('aria-hidden', 'true');
            this.element.removeAttribute('aria-modal');
            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach((backdrop) => backdrop.remove());
        }
    }

    window.bootstrap = window.bootstrap || {};
    window.bootstrap.Modal = Modal;

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-bs-toggle="modal"][data-bs-target]');
        if (trigger) {
            event.preventDefault();
            const target = document.querySelector(trigger.getAttribute('data-bs-target'));
            Modal.getOrCreateInstance(target).show();
            return;
        }

        const close = event.target.closest('[data-bs-dismiss="modal"]');
        if (close) {
            event.preventDefault();
            const target = close.closest('.modal');
            Modal.getOrCreateInstance(target).hide();
        }
    });
})();
