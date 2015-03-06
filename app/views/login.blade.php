{{-- This shows the sign in buttons for the various services. We start with facebook --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login To Lebanese Blogs</title>
  <link rel="stylesheet" href="{{asset('/css/login.css')}}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="modalWindow">


@if (Session::has('message'))
<h3>{{Session::get('message')}}</h3>
@endif

<img src="{{asset('/img/square-logo.png')}}" width="60px" height="60px" alt="Lebanese Blogs Logo">
<div class="tip">Bloggers! Sign in with twitter so you can edit your profile and posts</div>
<a class ="btn facebook "href="{{URL::to('/auth/facebook')}}">Sign In With Facebook</a>
<a class ="btn twitter "href="{{URL::to('/auth/twitter')}}">Sign In With Twitter</a>
<a class ="btn google "href="{{URL::to('/auth/google')}}">Sign In With Google</a>
<p>We respect your privacy. We won't share your email and we won't spam you. We only get in touch with you if something is wrong with your account</p>

</div>

</body>
</html>
