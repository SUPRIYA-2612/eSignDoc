<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>eSign Document Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 40px;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        th, td {
            padding: 14px 18px;
            background-color: #ffffff;
            text-align: center;
        }

        th {
            background-color: #343a40;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
        }

        a:hover {
            color: #0056b3;
        }

        .status-signed {
            color: #28a745;
            font-weight: bold;
        }

        .status-pending {
            color: #fd7e14;
            font-weight: bold;
        }

        .btn-link {
            display: inline-block;
            margin: 0 5px;
        }

        .icon {
            margin-right: 4px;
        }

        td[colspan="6"] {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>

<body>

    <h2><i class="fas fa-file-signature"></i> eSign Document Dashboard</h2>

    @include('notification')
    @include('upload')

    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Title</th>
                <th>Original File</th>
                <th>Status</th>
                <th>Signed File</th>
                <th>Signature</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documents as $doc)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $doc->title }}</td>
                    <td>
                        <a href="{{ asset('storage/document/'.$doc->original_file) }}" target="_blank">
                            <i class="fas fa-file-pdf icon"></i>View
                        </a>
                    </td>
                    <td>
                        @if($doc->is_signed)
                            <span class="status-signed"><i class="fas fa-check-circle icon"></i>Signed</span>
                        @else
                            <span class="status-pending"><i class="fas fa-clock icon"></i>Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($doc->is_signed)
                            <a href="{{ asset('storage/sign-document/'.$doc->signed_file) }}" target="_blank">
                                <i class="fas fa-file-signature icon"></i>View Signed
                            </a>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($doc->drawn_signature_file)
                            <a href="{{ asset('storage/sign/'.$doc->drawn_signature_file) }}" target="_blank">
                                <i class="fas fa-pen-nib icon"></i>View Signature
                            </a>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if(!$doc->is_signed)
                            <a href="{{ route('sign.form', $doc->id) }}" class="btn-link">
                                ✍️ Sign
                            </a>
                        @else
                            <a href="{{ route('preview', $doc->id) }}" class="btn-link">
                                <i class="fas fa-eye icon"></i>Preview
                            </a> |
                            <a href="{{ route('download', $doc->id) }}" class="btn-link">
                                <i class="fas fa-download icon"></i>Download
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" align="center">No documents uploaded yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
