<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{asset('css/home.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
    </style>

</head>

<title>首頁</title>
</head>

<body>
    <div class="container">
        <header class="row d-flex">
            <i class="fa-solid fa-bars" style="color: #616161;"></i>
            <i class="fa-solid fa-gear" style="color: #616161;"></i>
        </header>

        <div class="row d-flex content">
            <!-- 文字區 -->
            <div class="left">
                <div class="title">
                    <p>Welcome<br>Home</p>
                </div>

                <div class="date d-flex">
                    <div class="dleft d-flex flex-column">
                        <p class="year">{{ $y }}</p>
                        <p class="month">{{ $m }}&nbsp;/</p>
                    </div>
                    <div class="dright d-flex">
                        <p class="day">{{ $d }}</p>
                        <p class="week">{{ $w }}</p>
                    </div>
                </div>
            </div>

            <!-- 貼圖區 -->
            <div class="right">
                <img src="{{ asset('storage/img/serprise.png') }}" alt="首頁插圖">
            </div>


        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>