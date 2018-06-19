<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name') }}</title>

  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  <div id="boilerplate" data-app-name="{{ config('app.name') }}">
    <boilerplate-nav></boilerplate-nav>
    <default-transition>
      <boilerplate-admin v-if="isLoggedIn"></boilerplate-admin>
      <boilerplate-login v-else></boilerplate-login>
    </default-transition>
    <b-loading :is-full-page="true" :active="isLoading"></b-loading>
  </div>
  @if ($user)
  <script>
  window.user = {!! $user !!}
  </script>
  @endif
  <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
