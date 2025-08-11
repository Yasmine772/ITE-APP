<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Advices Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Amiri", serif;
        }

        .header-section {
            background: linear-gradient(90deg, rgba(8, 31, 136, 1) 30%, rgba(6, 71, 162, 1) 45%, rgba(5, 106, 185, 1) 62%, rgba(3, 147, 212, 1) 75%, rgba(0, 212, 255, 1) 96%);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .header-section h1 {
            font-weight: bold;
        }

        .advice-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <header class="header-section">
        <h1>Advices <i class="bi-lightbulb"></i></h1>
    </header>

    <main class="container mt-4">
        <div class="row justify-content-start">
            <div class="col-md-12">
                <div class="advice-list">
                    @foreach ($advices as $advice)
                    <div class="advice-item">
                        <p class="mb-0 fw-bold">{{ $advice->content }}</p>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-light">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-light"onclick="confirmDelete({{$advice->id}})">
                                <i class="bi bi-trash"></i>
                            </button>
                            <form id="delete-form-{{$advice->id}}" action="{{route('advices.deleteAdvice', $advice) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE') </form>
                        </div>
                    </div>
                    @endforeach
                    <script>
                        function confirmDelete(adviceId) {
                            if (confirm('Are you sure ?')) {
                                document.getElementById('delete-form-' + adviceId).submit();
                            }
                        }
                    </script>
                </div>
                <div class="d-grid mt-4">
                    <div class="d-flex justify-content-end mt-4">
                        <button class="btn btn-primary btn-lg" type="button">Add advice</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>