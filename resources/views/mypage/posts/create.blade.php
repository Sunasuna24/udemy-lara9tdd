@extends('layouts.app')

@section('content')
<h1>マイブログ新規登録</h1>
<form action="" method="post">
    @csrf
    @include('inc.error')
    <label>タイトル：</label><input type="text" name="title" value="{{ old('title') }}" style="width: 400px;">
    <br>
    <label>本文：</label><textarea name="body" style="width: 600px; height: 200px;">{{ old('body') }}</textarea>
    <br>
    公開する：<label><input type="checkbox" name="status" value="1" {{ old('status') ? 'checked' : '' }}>公開する</label>
    <br>
    <br>
    <input type="submit" value="送信する">
</form>
@endsection