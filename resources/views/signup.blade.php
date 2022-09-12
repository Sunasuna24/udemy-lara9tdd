@extends('layouts.app')

@section('content')

<h1>ユーザー登録</h1>
<form method="post">
@csrf

@include('inc.error')

<label>名前：</label><input type="text" name="name" value="{{ old('name') }}">
<br>
<label>メルアド：</label><input type="text" name="email" value="{{ old('email') }}">
<br>
<label>パスワード</label><input type="password" name="password">
<br><br>
<input type="submit" value="送信する">

</form>

@endsection