<h3 style="color: #2c3e50; margin-top: 30px;">📤 Sign document <strong>{{ $document->title }}</strong></h3>
<p style="margin-bottom: 20px;">
    <a href="{{ route('dashboard') }}" style="color: #007bff; text-decoration: none;">← Back to Dashboard</a>
</p>

@include('notification')

<form action="{{ route('documents.submitSignature', $document->id) }}" method="POST" class="signature-form">
    @csrf

    <div class="signature-option">
        <label>
            <input type="radio" name="signature_type" value="typed" checked> Typed Signature
        </label>
        <input type="text" name="typed_signature" placeholder="Enter your name" class="input-box">
    </div>

    <div class="signature-option">
        <label>
            <input type="radio" name="signature_type" value="drawn"> Draw Signature
        </label>

        <input type="hidden" name="drawn_signature" id="drawn_signature_input">
        <canvas id="signature_pad" width="400" height="200" class="signature-canvas"></canvas>
        <button type="button" onclick="saveSignature()" class="btn-secondary">💾 Save sign</button>
    </div>

    <button type="submit" class="btn-primary">📩 Submit Signature</button>
</form>

<!-- Signature Pad Script -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script>
    var signaturePad = new SignaturePad(document.getElementById('signature_pad'));

    function saveSignature() {
        if (!signaturePad.isEmpty()) {
            var dataUrl = signaturePad.toDataURL('image/png');
            document.getElementById('drawn_signature_input').value = dataUrl;
        } else {
            alert('Please draw your signature first.');
        }
    }
</script>

<style>
    .signature-form {
        background-color: #fff;
        padding: 25px 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        max-width: 650px;
        margin-top: 30px;
        margin-bottom: 50px;
    }

    .signature-option {
        margin-top: 20px;
    }

    .signature-option label {
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
        color: #34495e;
    }

    .input-box {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        margin-top: 5px;
        font-size: 14px;
    }

    .signature-canvas {
        display: block;
        border: 2px dashed #aaa;
        border-radius: 8px;
        margin: 10px 0;
    }

    .btn-primary, .btn-secondary {
        padding: 10px 18px;
        border-radius: 6px;
        font-size: 15px;
        cursor: pointer;
        border: none;
        margin-top: 10px;
        transition: background-color 0.3s ease;
    }

    .btn-primary {
        background-color: #28a745;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #218838;
    }

    .btn-secondary {
        background-color: #ffc107;
        color: #000;
    }

    .btn-secondary:hover {
        background-color: #e0a800;
    }
</style>
