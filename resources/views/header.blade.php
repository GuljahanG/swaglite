<div class="sl-header">

    <div class="swaglite-container">
        <h1>🚀 SwagLite</h1>
    </div>

    <div class="sl-actions">

        <input
            type="text"
            id="endpointSearch"
            placeholder="Search endpoints..."
        >

        <button
            class="btn btn-dark"
            id="themeToggle"
        >
            🌙
        </button>

        <div class="auth">

            <button
                id="authToggle"
                class="auth-btn"
                title="Authorization"
            >
                <svg id="authIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"></svg>
            </button>

            <div id="authPopup" class="auth-popup">

                <input
                    type="password"
                    id="bearer-token"
                    placeholder="Bearer Token"
                    autocomplete="off"
                >

                <button
                    id="save-token"
                    class="btn btn-primary"
                >
                    Save
                </button>

                <button
                    id="remove-token"
                    class="btn btn-danger"
                    style="display:none;"
                >
                    Remove
                </button>

            </div>

        </div>

    </div>

</div>

<style>
/* ==========================================
   HEADER
   ========================================== */

.sl-header{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:16px;
    padding:14px 24px;
    margin-bottom:30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    position:sticky;
    top:15px;
    z-index:100;
}

.sl-header h1{
    font-size:32px;
    margin-bottom:8px;
}

.sl-header p{
    color:var(--muted);
}

.sl-actions{
    display:flex;
    gap:12px;
    align-items:center;
}

.swaglite-container{
    display: flex;
}

.swaglite-container p{
    align-self: center;
    margin-left: 30px;
}

/* ==========================================
   SEARCH
   ========================================== */

#endpointSearch{
    width:320px;
    padding:12px 16px;
    border-radius:10px;
    border:1px solid var(--border);
    background:var(--card);
    color:var(--text);
    outline:none;
}

#endpointSearch:focus{
    border-color:#3b82f6;
}

.auth{
    position:relative;
}

.auth-btn{
    padding: 10px 14px;
    display:flex;
    align-items:center;
    justify-content:center;
    border:1px solid var(--border);
    border-radius:10px;
    background:var(--card);
    color:var(--text);
    cursor:pointer;
}
/* ==========================================
   Auth
   ========================================== */
.auth-popup{
    position:absolute;
    right:0;
    top:52px;
    width:280px;
    padding:16px;
    background:var(--card);
    border:1px solid var(--border);
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
    display:none;
    z-index:1000;
}

.auth-popup.show{
    display:block;
}

.auth-popup input{
    width:100%;
    margin-bottom:12px;
}

.auth-popup .btn{
    width:100%;
    margin-top:8px;
}
.auth input
{
    padding: 10px 14px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--sl-card);
    color: var(--sl-text);
    outline: none;
    transition: .2s;
}

.auth input:focus
{
    border-color: #2563eb;
    box-shadow:
        0 0 0 3px rgba(37,99,235,.15);
}
.auth button
{
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: .2s;
}

.auth button:hover
{
    transform: translateY(-1px);
}
</style>
<script>
    const authToggle = document.getElementById('authToggle');
const authPopup = document.getElementById('authPopup');

const tokenInput = document.getElementById('bearer-token');
const saveBtn = document.getElementById('save-token');
const removeBtn = document.getElementById('remove-token');
const icon = document.getElementById('authIcon');

const LOCK = `
<path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4M4.5 7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7zM8 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
`;

const UNLOCK = `
<path fill-rule="evenodd" d="M12 0a4 4 0 0 1 4 4v2.5h-1V4a3 3 0 1 0-6 0v2h.5A2.5 2.5 0 0 1 12 8.5v5A2.5 2.5 0 0 1 9.5 16h-7A2.5 2.5 0 0 1 0 13.5v-5A2.5 2.5 0 0 1 2.5 6H8V4a4 4 0 0 1 4-4M2.5 7A1.5 1.5 0 0 0 1 8.5v5A1.5 1.5 0 0 0 2.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 9.5 7z"/>
`;

function updateAuthUI()
{
    const token = localStorage.getItem('swaglite_bearer_token');

    if(token)
    {
        icon.innerHTML = LOCK;

        tokenInput.value = "**************";

        saveBtn.style.display = "none";
        removeBtn.style.display = "block";
    }
    else
    {
        icon.innerHTML = UNLOCK;

        tokenInput.value = "";

        saveBtn.style.display = "block";
        removeBtn.style.display = "none";
    }
}

authToggle.onclick = () =>
{
    authPopup.classList.toggle('show');

    if(localStorage.getItem('swaglite_bearer_token'))
    {
        tokenInput.type = "password";
    }
};

saveBtn.onclick = () =>
{
    localStorage.setItem(
        'swaglite_bearer_token',
        tokenInput.value
    );

    authPopup.classList.remove('show');

    updateAuthUI();
};

removeBtn.onclick = () =>
{
    localStorage.removeItem('swaglite_bearer_token');

    authPopup.classList.remove('show');

    updateAuthUI();
};

document.addEventListener('click', e =>
{
    if(!authPopup.contains(e.target) && !authToggle.contains(e.target))
    {
        authPopup.classList.remove('show');
    }
});

updateAuthUI();
</script>