<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Trang đăng nhập</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    body {
      width: 100%;
      font-family: "Inter", sans-serif;
    }

    .btn-google {
      background: linear-gradient(90deg, #2a56f9 0%, #d92efb 100%);
    }

    .btn-login {
      background: linear-gradient(90deg, #a7bfff 0%, #f0bbff 100%);
      transition: background 0.3s ease;
    }

    .btn-login:hover {
      background: linear-gradient(90deg, #2a56f9 0%, #d92efb 100%);
    }

    .btn-login:disabled {
      cursor: not-allowed;
    }

    input:focus {
      outline: none;
      border-color: transparent;
      box-shadow: 0 0 0 2px #d92efb;
    }

    .alert-danger {
      color: red !important;
      background-color: transparent !important;
      border-color: transparent !important;
      padding: 0 !important;
      margin-bottom: 0 !important;
      font-size: 15px;
    }
  </style>
</head>

<body class="bg-[#f0f5ff] min-h-screen flex items-center justify-center p-4">
  <div class="bg-white rounded-lg shadow-md max-w-6xl w-full flex flex-col md:flex-row overflow-hidden">
    <div>
      <img
        alt="Illustration of a person sitting on a chair wearing purple clothes, surrounded by colorful stars and browser windows with design elements"
        class="w-full h-auto max-w-[600px]" height="600"
        src="https://storage.googleapis.com/a1aa/image/ffba455a-b7d3-4a8f-349a-30a31f785da5.jpg" width="600" />
    </div>
    <div class="md:w-1/2 p-10 flex flex-col justify-center">
      <h2 class="text-black text-lg font-normal mb-6 text-center">Đăng nhập</h2>
      <form method="POST" action="{{ route('auth.login') }}" class="flex flex-col gap-3">
        @csrf
        <div class="flex flex-col">
          <label class="text-gray-900 text-sm font-normal mb-1" for="email">Tài khoản đăng nhập</label>
          <input name="email" type="text" value="{{ old('email') }}" placeholder="Nhập tài khoản hoặc email"
            class="border border-gray-300 rounded-md py-2 px-3 text-gray-500 placeholder-gray-400" />
          @error('email')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror
        </div>
        <div class="flex flex-col relative">
          <label class="text-gray-900 text-sm font-normal mb-1" for="password">Mật khẩu</label>
          <input name="password" type="password" placeholder="Nhập mật khẩu của bạn"
            class="border border-gray-300 rounded-md py-2 px-3 pr-10 text-gray-500 placeholder-gray-400" />
          @error('password')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror
          @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

        </div>
        <button type="submit" class="btn-login w-full py-3 rounded-md text-white font-semibold mt-5">
          Đăng nhập
        </button>
      </form>
    </div>                                          
  </div>
</body>


</html>