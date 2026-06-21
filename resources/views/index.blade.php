<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwagLite</title>

    <link rel="stylesheet" href="{{ asset('vendor/swaglite/swaglite.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>
<body>

<div class="sl-header">

    <div>
        <h1>🚀 SwagLite</h1>
        <p>
            Laravel API Documentation
        </p>
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
            🌙 Dark Mode
        </button>

    </div>

</div>

@foreach($routes->groupBy('group') as $group => $items)

    <div class="group">

        <span class="group-title">
            <img
                class="folder"
                width="22"
                height="22"
                src="{{ asset('vendor/swaglite/folder.png') }}"
            >

            <img
                class="open-folder"
                width="22"
                height="22"
                src="{{ asset('vendor/swaglite/open-folder.png') }}"
            >
            <div class="title">
                {{ $group ?: 'General' }} <span> ({{ $items->count() }}) </span>
            </div>
 
        </span>

        <div class="group-body">

            @foreach($items as $route)
            

                @php
                    $method = trim(
                        explode(',', $route['methods'])[0]
                    );
                @endphp

                <div
                    class="endpoint {{ strtolower($method) }}"
                    data-search="
                        {{ strtolower($group) }}
                        {{ strtolower($route['uri']) }}
                        {{ strtolower($route['controller']) }}
                        {{ strtolower($route['method']) }}
                        {{ strtolower($route['description']) }}
                    "
                >

                    <div class="endpoint-header">

                        <div class="method">
                            {{ $method }}
                        </div>

                        <div class="uri">

                            <div class="uri-path">
                                /{{ $route['uri'] }}
                            </div>

                            <span class="summary">
                                {{ $route['description'] ?: 'No description provided' }}
                            </span>

                        </div>

                        <div class="endpoint-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
                            </svg>
                        </div>

                    </div>

                    <div class="endpoint-content">

                        <div class="top-actions">

                            <button
                                class="btn btn-copy copy-url"
                                data-url="/{{ $route['uri'] }}"
                            >
                                Copy URL
                            </button>

                            <button
                                class="btn btn-copy copy-curl"
                                data-method="{{ $method }}"
                                data-url="/{{ $route['uri'] }}"
                            >
                                Copy cURL
                            </button>

                        </div>

                        <div class="grid route-card" data-route="{{ $route['id'] }}">
   
                            <div class="section">
                                <div class="section-title">
                                    Parameters
                                </div>
                            
                                @forelse($route['parameters'] as $parameter)

                                    <div class="param">

                                        <label>
                                            {{ $parameter->name }}
                                            <small>
                                                ({{ $parameter->in }})
                                            </small>
                                        </label>

                                        <input
                                            type="text"
                                            name="{{ $parameter->name }}"
                                            data-name="{{ $parameter->name }}"
                                            data-in="{{ $parameter->in }}"
                                            placeholder="{{ $parameter->example ?? $parameter->name }}"
                                        >

                                        @if($parameter->description)
                                            <small>
                                                {{ $parameter->description }}
                                            </small>
                                        @endif

                                    </div>

                                @empty

                                    <div class="empty-state">
                                        No parameters required.
                                    </div>

                                @endforelse

                            
                                <div
                                    class="section-title"
                                    style="margin-top:25px;"
                                >
                                    Example Request
                                </div>
                            </div>
                            
                            
                            <div class="section">

                                <div class="section-title">
                                    Route Information
                                </div>

                                <pre>{
    "controller": "{{ class_basename($route['controller']) }}",
    "method": "{{ $route['method'] }}",
    "http_method": "{{ $method }}",
    "uri": "/{{ $route['uri'] }}"
}</pre>

                                <button
                                    class="execute-btn"
                                    data-id="{{ $route['id'] }}"
                                    data-method="{{ $method }}"
                                    data-uri="/{{ $route['uri'] }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play" viewBox="0 0 16 16">
                                        <path d="M10.804 8 5 4.633v6.734zm.792-.696a.802.802 0 0 1 0 1.392l-6.363 3.692C4.713 12.69 4 12.345 4 11.692V4.308c0-.653.713-.998 1.233-.696z"/>
                                    </svg>
                             Execute
                                </button>

                                {{-- Example Response --}}
                                <div class="section-title" style="margin-top:25px;" >
                                    Live Response
                                </div>

                                <pre class="response-box" id="response-{{ $route['id'] }}">
{
    "message": "Click Execute"
}
                                </pre>
                                @foreach($route['responses'] as $response)

                                    <div class="response-card">

                                        <div class="response-header">

                                            <span class="badge">
                                                {{ $response['status'] }}
                                            </span>

                                            {{ $response['description'] }}

                                        </div>

                                        <pre>
{{ json_encode(
    $response['example'],
    JSON_PRETTY_PRINT
) }}
                                        </pre>

                                    </div>

                                @endforeach

                            </div>
            
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

@endforeach

<script src="{{ asset('vendor/swaglite/swaglite.js') }}"></script>

</body>
</html>
