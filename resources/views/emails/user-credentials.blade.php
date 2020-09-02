@component('mail::message')
# Introduction

Hello! {{$user->name}}
This will be your username for the Online Loan Application <br>
Email: <b>{{$user->email}}</b> <br>
Password: <b>{{$user->pstring}}</b>
@component('mail::button', ['url' => 'https://laf.light.org.ph/'])
Go to site
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
