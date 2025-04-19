<embed src="{{ asset('storage/sign-document/' . $document->signed_file) }}" width="100%" height="600px" />
<a href="{{ route('download', $document->id) }}">Download PDF</a>

