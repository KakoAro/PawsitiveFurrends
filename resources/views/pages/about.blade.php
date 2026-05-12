@extends('layouts.app')

@section('title', 'About Us - Pawsitive Furrends')

@section('content')
<div style="padding-top:80px"></div>

<section class="py-5">
    <div class="container" style="max-width:800px">
        <div class="text-center mb-5">
            <h1 class="font-display">About Pawsitive Furrends</h1>
            <p class="lead text-muted">Connecting loving animals with loving homes</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius:1.5rem;background:var(--card-bg)">
                    <div class="card-body p-4">
                        <h3 class="font-display">Our Mission</h3>
                        <p class="text-muted">
                            At Pawsitive Furrends, we believe every animal deserves a loving home. 
                            Our mission is to connect abandoned and stray animals with caring 
                            individuals and families who can provide them with the love, care, 
                            and stability they need to thrive.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius:1.5rem;background:var(--card-bg)">
                    <div class="card-body p-4">
                        <h3 class="font-display">Our Story</h3>
                        <p class="text-muted">
                            Created in 2026 by a group of BSIT students from University of Mindanao Tagum College, Pawsitive Furrends 
                            started as a small local initiative to help stray animals in our 
                            community. Today, we've grown to help hundreds of animals find 
                            their forever homes each year.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius:1.5rem;background:var(--card-bg)">
                    <div class="card-body p-4">
                        <h3 class="font-display">How We Work</h3>
                        <p class="text-muted">
                            We work closely with local shelters to 
                            identify animals in need. Each animal in our program receives 
                            veterinary care, vaccinations, and services before 
                            being made available for adoption. Our thorough application process 
                            ensures that each animal is matched with a suitable family based 
                            on lifestyle, experience, and preferences.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection