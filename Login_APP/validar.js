document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('registroForm');
    const passInput = document.getElementById('pass');

    // ── 1. VALIDACIÓN AL ENVIAR EL FORMULARIO ──────────────────
    if (formulario) {
        formulario.addEventListener('submit', (e) => {
            const pass = passInput.value;
            const nombre = document.getElementById('nombre').value;
            const fechaNacValue = document.getElementById('fecha_nac').value;
            const hoy = new Date();

            // --- Validar Nombre ---
            if (nombre.trim().length < 3) {
                e.preventDefault();
                alert("Por favor, ingresa tu nombre completo.");
                return;
            }

            // --- Validar Seguridad de Contraseña ---
            const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            if (!strongRegex.test(pass)) {
                e.preventDefault();
                alert("La contraseña no cumple con los requisitos de seguridad.");
                return;
            }

            // --- BLOQUEO ESTRICTO DE EDAD (Mínimo 18 años) ---
            if (fechaNacValue) {
                const fechaNac = new Date(fechaNacValue);
                
                // Cálculo preciso de edad
                let edad = hoy.getFullYear() - fechaNac.getFullYear();
                const mes = hoy.getMonth() - fechaNac.getMonth();
                
                // Si el mes actual es menor al de nacimiento, o es el mismo mes pero 
                // el día actual es menor, aún no cumple años.
                if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
                    edad--;
                }

                if (edad < 18) {
                    e.preventDefault(); // BLOQUEO TOTAL: Evita que el formulario llegue al PHP
                    alert("Acceso denegado: Debes ser mayor de 18 años para registrarte en Remolinos Tours.");
                    
                    // Resaltado visual del error
                    const inputFecha = document.getElementById('fecha_nac');
                    inputFecha.style.borderColor = "#f87171"; // Rojo error
                    inputFecha.style.boxShadow = "0 0 0 3px rgba(248, 113, 113, 0.2)";
                    inputFecha.focus();
                    return; // Corta la ejecución aquí
                }
            }
        });
    }

    // ── 2. SHOW / HIDE CONTRASEÑA (EL OJO) ──────────────────────
    const SVG_OJO_ABIERTO = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
    const SVG_OJO_CERRADO = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;

    document.querySelectorAll('.eye-toggle').forEach(btn => {
        const inputTarget = document.getElementById(btn.dataset.target);
        if (!inputTarget) return;

        btn.innerHTML = SVG_OJO_CERRADO;

        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const isVisible = inputTarget.type === 'text';
            inputTarget.type = isVisible ? 'password' : 'text';
            btn.innerHTML = isVisible ? SVG_OJO_CERRADO : SVG_OJO_ABIERTO;
        });
    });

    // ── 3. FORTALEZA Y REQUISITOS (EN TIEMPO REAL) ──────────────
    const segs = [document.getElementById('seg1'), document.getElementById('seg2'), document.getElementById('seg3')];
    const hintLen = document.getElementById('hint-len');
    const hintUp = document.getElementById('hint-up');
    const hintSp = document.getElementById('hint-sp');

    if (passInput && segs[0]) {
        passInput.addEventListener('input', () => {
            const v = passInput.value;
            let score = 0;

            if (v.length >= 8) {
                score++;
                if (hintLen) hintLen.classList.add('completed');
            } else {
                if (hintLen) hintLen.classList.remove('completed');
            }

            if (/[A-Z]/.test(v) && /[0-9]/.test(v)) {
                score++;
                if (hintUp) hintUp.classList.add('completed');
            } else {
                if (hintUp) hintUp.classList.remove('completed');
            }

            if (/[\W_]/.test(v)) {
                score++;
                if (hintSp) hintSp.classList.add('completed');
            } else {
                if (hintSp) hintSp.classList.remove('completed');
            }

            const clasesFuerza = ['weak', 'medium', 'strong'];
            segs.forEach((seg, i) => {
                if (!seg) return;
                seg.className = 'strength-seg';
                if (i < score) {
                    seg.classList.add(clasesFuerza[score - 1]);
                }
            });
        });
    }
});