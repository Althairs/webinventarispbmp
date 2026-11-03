<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Registration</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Registration Form</h1>
    
    @if(session('notification'))
        <div style="background: green; color: white; padding: 10px; margin: 10px 0;">
            {{ session('notification.message') }}
        </div>
    @endif
    
    @if($errors->any())
        <div style="background: red; color: white; padding: 10px; margin: 10px 0;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div>
            <label for="nama_lengkap">Nama Lengkap:</label><br>
            <input type="text" name="nama_lengkap" id="nama_lengkap" required value="{{ old('nama_lengkap') }}">
        </div><br>
        
        <div>
            <label for="instansi">Instansi:</label><br>
            <input type="text" name="instansi" id="instansi" required value="{{ old('instansi') }}">
        </div><br>
        
        <div>
            <label for="nip">NIP:</label><br>
            <input type="text" name="nip" id="nip" required value="{{ old('nip') }}">
        </div><br>
        
        <div>
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username" required value="{{ old('username') }}">
        </div><br>
        
        <div>
            <label for="email">Email:</label><br>
            <input type="email" name="email" id="email" required value="{{ old('email') }}">
        </div><br>
        
        <div>
            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required>
        </div><br>
        
        <div>
            <label for="role_request">Role Request:</label><br>
            <select name="role_request" id="role_request" required>
                <option value="user" {{ old('role_request') == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role_request') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div><br>
        
        <button type="submit">Register</button>
    </form>
    
    <hr>
    <p><a href="{{ route('login') }}">Back to Login</a></p>
</body>
</html>
