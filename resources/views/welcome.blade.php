@extends('layouts.public')

@section('content')
<div style="max-width: 500px; margin: 3rem auto; background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); overflow: hidden;">
    <div style="height: 10px; background: #e76f51; width: 100%;"></div>
    <div style="display: flex; flex-direction: column; align-items: center; padding: 2.5rem 2rem 2rem 2rem;">
        <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=800&q=80" alt="Modern office building" style="width: 100%; max-width: 320px; border-radius: 12px; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
        
        <h1 class="text-2xl font-bold mb-4 text-primary-orange">Welcome to Company</h1>
        
        <p class="text-lg mb-6" style="max-width: 400px; text-align: center; color: #6b7280;">
            Effortlessly manage, organize, and access all your company information in one secure place. Track company details, contacts, and more with ease.
        </p>
    </div>
</div>
@endsection
