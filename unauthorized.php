<?php
$pageTitle = 'Akses Ditolak';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Sky University</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            padding: 1rem;
        }
        
        .error-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 3rem 2rem;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        
        .error-icon {
            font-size: 4rem;
            color: #ef4444;
            margin-bottom: 1.5rem;
        }
        
        .error-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        
        .error-message {
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card sky-fade-in">
            <i class="fas fa-ban error-icon"></i>
            <h1 class="error-title">Akses Ditolak</h1>
            <p class="error-message">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. 
                Silakan hubungi administrator sistem jika Anda merasa ini adalah kesalahan.
            </p>
            <a href="index.php" class="btn-primary">
                <i class="fas fa-home"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
