// ==========================================
// SwagLite v1
// ==========================================

document.addEventListener('DOMContentLoaded', () => {

    initGroups();
    initEndpoints();
    initSearch();
    initTheme();
    initCopyUrl();
    initCopyCurl();
    initTokenStore();

});

// ==========================================
// GROUP ACCORDION
// ==========================================

function initGroups()
{
    document
        .querySelectorAll('.group-title')
        .forEach(button => {

            button.addEventListener('click', () => {

                const group =
                    button.closest('.group');

                group.classList.toggle('active');

            });

        });

    // Open first group automatically

    const firstGroup =
        document.querySelector('.group');

    if(firstGroup)
    {
        firstGroup.classList.add('active');
    }
}

// ==========================================
// ENDPOINT ACCORDION
// ==========================================

function initEndpoints()
{
    document
        .querySelectorAll('.endpoint-header')
        .forEach(button => {

            button.addEventListener('click', () => {

                const endpoint =
                    button.closest('.endpoint');

                endpoint.classList.toggle('active');

            });

        });
}

// ==========================================
// SEARCH
// ==========================================

function initSearch()
{
    const search =
        document.getElementById(
            'endpointSearch'
        );

    if(!search)
    {
        return;
    }

    search.addEventListener('input', () => {

        const value =
            search.value
                .trim()
                .toLowerCase();

        document
            .querySelectorAll('.endpoint')
            .forEach(endpoint => {

                const text =
                    endpoint.dataset.search || '';

                const match =
                    text.includes(value);

                endpoint.style.display =
                    match
                        ? ''
                        : 'none';

                if(match)
                {
                    const group =
                        endpoint.closest('.group');

                    if(group)
                    {
                        group.classList.add('active');
                    }
                }

            });

    });
}

// ==========================================
// DARK MODE
// ==========================================

function initTheme()
{
    const button =
        document.getElementById(
            'themeToggle'
        );

    if(!button)
    {
        return;
    }

    const currentTheme =
        localStorage.getItem(
            'swaglite-theme'
        );

    if(currentTheme === 'dark')
    {
        document.body.classList.add(
            'dark'
        );

        button.innerHTML =
            '☀ Light Mode';
    }

    button.addEventListener('click', () => {

        document.body.classList.toggle(
            'dark'
        );

        const dark =
            document.body.classList.contains(
                'dark'
            );

        localStorage.setItem(
            'swaglite-theme',
            dark
                ? 'dark'
                : 'light'
        );

        button.innerHTML =
            dark
                ? '☀ Light Mode'
                : '🌙 Dark Mode';

    });
}

// ==========================================
// COPY URL
// ==========================================

function initCopyUrl()
{
    document
        .querySelectorAll('.copy-url')
        .forEach(button => {

            button.addEventListener(
                'click',
                event => {

                    event.stopPropagation();

                    navigator.clipboard.writeText(
                        button.dataset.url
                    );

                    flashButton(
                        button,
                        'Copied!'
                    );

                }
            );

        });
}

// ==========================================
// COPY CURL
// ==========================================

function initCopyCurl()
{
    document
        .querySelectorAll('.copy-curl')
        .forEach(button => {

            button.addEventListener(
                'click',
                event => {

                    event.stopPropagation();

                    const method =
                        button.dataset.method;

                    const url =
                        button.dataset.url;

                    const curl =

`curl -X ${method} \
"${window.location.origin}${url}" \
-H "Accept: application/json"`;

                    navigator.clipboard.writeText(
                        curl
                    );

                    flashButton(
                        button,
                        'Copied!'
                    );

                }
            );

        });
}

// ==========================================
// BUTTON FEEDBACK
// ==========================================

function flashButton(
    button,
    text
)
{
    const original =
        button.innerHTML;

    button.innerHTML = text;

    setTimeout(() => {

        button.innerHTML =
            original;

    }, 1500);
}

// ==========================================
// UTILITIES
// ==========================================

function copy(text)
{
    navigator.clipboard.writeText(
        text
    );
}

function prettyJson(data)
{
    return JSON.stringify(
        data,
        null,
        4
    );
}

// ==========================================
// Save TOKEN
// ==========================================
function initTokenStore(){

    const input = document.getElementById('bearer-token');

    input.value = localStorage.getItem('swaglite_token') || '';

    document
        .getElementById('save-token')
        .addEventListener('click', () => {

            localStorage.setItem(
                'swaglite_token',
                input.value
            );
            console.log('swaglite_token', input.value);

        });
}


document.addEventListener('click', async event => {

    if (
        !event.target.classList.contains(
            'execute-btn'
        )
    ) {
        return;
    }

    const button = event.target;

    const routeId =
        button.dataset.id;

    const method =
        button.dataset.method;

    const uri =
        button.dataset.uri;

    const responseBox =
        document.getElementById(
            'response-' + routeId
        );
    const token =
        localStorage.getItem('swaglite_token');

    try {

        button.innerText =
            'Loading...';

        let finalUri = uri;
        const body = {};

        const routeCard =
            button.closest('.route-card');

        if (routeCard) {

        const pathInputs =
            routeCard.querySelectorAll(
                '[data-in="path"]'
            );

            pathInputs.forEach(input => {

                const value =
                    input.value.trim();

                if (!value) {
                    throw new Error(
                        `${input.name} is required`
                    );
                }

                finalUri =
                    finalUri.replace(
                        `{${input.dataset.name}}`,
                        encodeURIComponent(
                            value
                        )
                    );

            });

            const bodyInputs =
                routeCard.querySelectorAll(
                    '[data-in="body"]'
                );

            bodyInputs.forEach(input => {

                body[input.dataset.name] =
                    input.value;

            });

        }

        console.log(
            'Request URL:',
            finalUri
        );

        const started =
            performance.now();

        const options = {
            method: method,
            headers: {
                Accept: 'application/json',
                ...(token && {
                    Authorization: `Bearer ${token}`
                })
            }
        };

        if (
            ['POST', 'PUT', 'PATCH']
                .includes(method)
        ) {

            options.headers[
                'Content-Type'
            ] = 'application/json';

            options.body =
                JSON.stringify(body);

        }


        const response =
            await fetch(
                finalUri,
                options
            );

        const ended =
            performance.now();

        const duration =
            Math.round(
                ended - started
            );

        let data;

        try {

            data =
                await response.json();

        } catch {

            data =
                await response.text();

        }

        responseBox.textContent =
`Status: ${response.status}

Time: ${duration}ms

${typeof data === 'string'
    ? data
    : JSON.stringify(
        data,
        null,
        4
    )}`;

    } catch (error) {

        responseBox.textContent =
`Request failed:

${error.message}`;

    } finally {

        button.innerText =
            '▶ Execute';

    }

});
