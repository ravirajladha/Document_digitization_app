{{-- Document Show View --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $documentName }}</title>
</head>
<body>
    <h1>{{ $documentName }}</h1>

    @foreach($filePaths as $type => $filePath)
        @if($type == 3) {{-- Image --}}
            <img src="{{ asset( $filePath) }}" alt="Image">
        @elseif($type == 4) {{-- PDF --}}
            <iframe src="{{ asset( $filePath) }}" width="100%" height="600px"></iframe>
        @elseif($type == 6) {{-- Video --}}
            <video width="320" height="240" controls>
                <source src="{{ asset( $filePath) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @endif
    @endforeach
</body>
</html>
