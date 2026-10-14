<!-- GLOBAL VALIDATION & ERROR POPUP MODAL -->
<div id="rkValidationModal" style="display: none; position: fixed; inset: 0; z-index: 999999; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 480px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid #fecaca; overflow: hidden; animation: rkPopAnim 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
        <!-- POPUP HEADER -->
        <div style="padding: 20px 24px; background: linear-gradient(135deg, #fff1f2 0%, #fee2e2 100%); border-bottom: 1px solid #fecdd3; display: flex; align-items: center; gap: 14px;">
            <div style="width: 42px; height: 42px; border-radius: 12px; background: #ffffff; display: flex; align-items: center; justify-content: center; color: #dc2626; font-size: 20px; box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.15); flex-shrink: 0;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div style="flex: 1; min-width: 0;">
                <h4 id="rkValidationTitle" style="margin: 0; font-size: 16px; font-weight: 800; color: #991b1b; line-height: 1.2;">
                    Validation Error
                </h4>
                <p style="margin: 3px 0 0; font-size: 12.5px; color: #b91c1c; font-weight: 500;">
                    Please correct the following errors
                </p>
            </div>
            <button type="button" onclick="closeValidationPopup()" style="background: transparent; border: none; font-size: 22px; color: #991b1b; cursor: pointer; padding: 4px; line-height: 1; border-radius: 6px;">
                &times;
            </button>
        </div>

        <!-- POPUP BODY -->
        <div style="padding: 20px 24px; max-height: 360px; overflow-y: auto;">
            <ul id="rkValidationList" style="margin: 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 10px;">
                @if(isset($errors) && $errors->any())
                    @foreach($errors->all() as $err)
                        <li style="display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; color: #991b1b; background: #fff1f2; padding: 10px 14px; border-radius: 10px; border: 1px solid #fecdd3; line-height: 1.4;">
                            <i class="fa-solid fa-circle-exclamation" style="margin-top: 2px; color: #ef4444; flex-shrink: 0;"></i>
                            <span>{{ $err }}</span>
                        </li>
                    @endforeach
                @elseif(session('error'))
                    <li style="display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; color: #991b1b; background: #fff1f2; padding: 10px 14px; border-radius: 10px; border: 1px solid #fecdd3; line-height: 1.4;">
                        <i class="fa-solid fa-circle-exclamation" style="margin-top: 2px; color: #ef4444; flex-shrink: 0;"></i>
                        <span>{{ session('error') }}</span>
                    </li>
                @endif
            </ul>
        </div>

        <!-- POPUP FOOTER -->
        <div style="padding: 14px 24px 18px; background: #fafafa; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end;">
            <button type="button" onclick="closeValidationPopup()" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: #ffffff; border: none; font-weight: 700; font-size: 13.5px; padding: 9px 22px; border-radius: 9px; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.25); transition: all 0.2s;">
                <i class="fa-solid fa-check" style="margin-right: 6px;"></i> Dismiss
            </button>
        </div>
    </div>
</div>

<style>
@keyframes rkPopAnim {
    from { opacity: 0; transform: scale(0.92) translateY(-10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
</style>

<script>
function showValidationPopup(title, errors) {
    const modal = document.getElementById('rkValidationModal');
    const titleEl = document.getElementById('rkValidationTitle');
    const listEl = document.getElementById('rkValidationList');
    if (!modal || !listEl) return;

    if (title && titleEl) titleEl.innerText = title;

    let html = '';
    const items = Array.isArray(errors) ? errors : [errors];
    items.forEach(function(msg) {
        html += `
            <li style="display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; color: #991b1b; background: #fff1f2; padding: 10px 14px; border-radius: 10px; border: 1px solid #fecdd3; line-height: 1.4;">
                <i class="fa-solid fa-circle-exclamation" style="margin-top: 2px; color: #ef4444; flex-shrink: 0;"></i>
                <span>${msg}</span>
            </li>
        `;
    });
    listEl.innerHTML = html;
    modal.style.display = 'flex';
}

function closeValidationPopup() {
    const modal = document.getElementById('rkValidationModal');
    if (modal) modal.style.display = 'none';
}

// Close popup on Escape key or outside click
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeValidationPopup();
});
document.addEventListener('click', function(e) {
    const modal = document.getElementById('rkValidationModal');
    if (modal && e.target === modal) {
        closeValidationPopup();
    }
});

// Auto-show backend validation errors on page load
document.addEventListener('DOMContentLoaded', function() {
    @if((isset($errors) && $errors->any()) || session('error'))
        const modal = document.getElementById('rkValidationModal');
        if (modal) modal.style.display = 'flex';
    @endif

    // Setup input filters and validation across all forms
    document.querySelectorAll('form').forEach(function(form) {
        // 1. Phone number sanitization and exact 10 digits enforcement
        const phoneInputs = form.querySelectorAll('input[name="phone"], input[type="tel"], input[name="mobile"], input[id*="phone"], input[id*="mobile"]');
        phoneInputs.forEach(function(pInput) {
            pInput.setAttribute('maxlength', '10');
            pInput.setAttribute('inputmode', 'numeric');
            pInput.setAttribute('autocomplete', 'tel');
            
            pInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
            });
            pInput.addEventListener('paste', function() {
                setTimeout(() => {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
                }, 10);
            });
        });

        // 2. Strict String/Name sanitization (no numbers in personal/staff/manager/city/role names)
        const nameInputs = form.querySelectorAll('input[name="name"], input[name="city"], input[name="state"], input[data-alpha-only="true"]');
        nameInputs.forEach(function(nInput) {
            const formAction = (form.getAttribute('action') || '').toLowerCase();
            const isNameStrict = formAction.includes('staff') || 
                                 formAction.includes('manager') || 
                                 formAction.includes('role') || 
                                 formAction.includes('admin') || 
                                 nInput.name === 'city' || 
                                 nInput.name === 'state' ||
                                 nInput.hasAttribute('data-alpha-only');
            
            if (isNameStrict) {
                nInput.addEventListener('input', function() {
                    if (/[0-9]/.test(this.value)) {
                        this.value = this.value.replace(/[0-9]/g, '');
                    }
                });
                nInput.addEventListener('paste', function() {
                    setTimeout(() => {
                        this.value = this.value.replace(/[0-9]/g, '');
                    }, 10);
                });
            }
        });

        // 3. Client-side submit interceptor for user-friendly popup
        form.addEventListener('submit', function(e) {
            const formErrors = [];

            // Validate phone inputs
            phoneInputs.forEach(function(pInput) {
                const val = pInput.value.trim();
                const isRequired = pInput.hasAttribute('required');

                if (isRequired && val.length === 0) {
                    formErrors.push("Phone / Mobile number is required!");
                    pInput.focus();
                } else if (val.length > 0 && val.length !== 10) {
                    formErrors.push(`Phone number must be exactly 10 digits (no more, no less)! You entered ${val.length} digits.`);
                    pInput.focus();
                }
            });

            // Validate strict string/name inputs (no digits)
            nameInputs.forEach(function(nInput) {
                const formAction = (form.getAttribute('action') || '').toLowerCase();
                const isNameStrict = formAction.includes('staff') || 
                                     formAction.includes('manager') || 
                                     formAction.includes('role') || 
                                     formAction.includes('admin') || 
                                     nInput.name === 'city' || 
                                     nInput.name === 'state' ||
                                     nInput.hasAttribute('data-alpha-only');

                if (isNameStrict) {
                    const val = nInput.value.trim();
                    if (/[0-9]/.test(val)) {
                        const fieldName = nInput.name === 'city' ? 'City' : (nInput.name === 'state' ? 'State' : 'Name');
                        formErrors.push(`${fieldName} field cannot contain numbers! Please enter letters only.`);
                        nInput.focus();
                    }
                }
            });

            // Validate price / fees
            const priceInputs = form.querySelectorAll('input[name="price"], input[name="fee"]');
            priceInputs.forEach(function(prInput) {
                const val = parseFloat(prInput.value);
                if (!isNaN(val) && val < 0) {
                    formErrors.push("Test fee / price cannot be negative! Please enter a valid amount.");
                    prInput.focus();
                }
            });

            if (formErrors.length > 0) {
                e.preventDefault();
                showValidationPopup("Form Validation Error", formErrors);
            }
        });
    });
});
</script>
