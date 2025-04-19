
  


    <h3>📤 Upload Document for eSignature</h3>

    <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data" class="form-box">
        @csrf

        <label for="title">Document Title:</label>
        <input type="text" name="title" id="title" placeholder="e.g. Service Agreement" required>

        <label for="document">Select PDF/DOCX File:</label>
        <input type="file" name="document" id="document" accept=".pdf, .doc, .docx" required>

        <button type="submit">Upload Document</button>
    </form>

