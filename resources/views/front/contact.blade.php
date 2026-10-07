@extends('front.fixe')

@section('titre', 'Contact | Korean Skincare Tunisia ')

@section('meta')
    <!-- SEO META TAGS -->
    <meta name="description" content="Contact Korean Glam By Ameni, the reference for Korean Skincare in Tunisia. Get in touch for orders, consultations, or questions about K-Beauty products.">
    <meta name="keywords" content="contact korean skincare tunisia, korean glam contact, k-beauty tunisia, skincare store tunisia">
@endsection
@section('body')

    <div class="container">
        <br><br><br>
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <h5><b>Drop us message</b></h5>
                    <form action="{{ route('contact_post') }}" method="post">
                        <x-FrontAlert></x-FrontAlert>
                        @csrf
                        <div class="mb-3">
                            <label for="name">Your name</label>
                            <input type="text" class="form-control" placeholder="Enter your name" name="nom" required id="name">
                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone">Your phone</label>
                            <input type="tel" class="form-control" placeholder="Enter your phone" required name="telephone"
                                id="phone">
                            @error('phone')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email">Your email</label>
                            <input type="email" class="form-control" placeholder="Enter your email" required name="email"
                                id="email">
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="message">Your message</label>
                            <textarea class="form-control" placeholder="Enter your message" required name="message" id="message"></textarea>
                            @error('message')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <button class="btn btn-primary-color w-100" type="submit">
                            Submit
                        </button>
                    </form>
                </div>
                <div class="col-sm-6">
                    <h5><b>Get in touch</b></h5>
                    <div>
                        <table>
                            <tr>
                                <td class="icon-form-contact"><i class="fa-solid fa-location-dot"></i></td>
                                <td>
                                    <b>Address</b> <br>
                                    <p>{{ $infos->adresse ?? ''}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="icon-form-contact"><i class="fa-solid fa-phone"></i></td>
                                <td>
                                    <b>Phone</b> <br>
                                    <p>{{ $infos->telephone ?? ''}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="icon-form-contact"><i class="fa-solid fa-envelope"></i></td>
                                <td>
                                    <b>Email</b> <br>
                                    <p>{{ $infos->email ?? ''}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="icon-form-contact"><i class="fa-brands fa-square-instagram"></i></td>
                                <td>
                                    <b>Instagram</b><br>
                                    <p><a href="{{ $infos->instagram ?? '#'}}" target="_blank">{{ $infos->instagram ?? ''}}</a></p>
                                </td>
                            </tr>
                            <tr>
                                <td class="icon-form-contact"><i class="fa-brands fa-square-whatsapp"></i></td>
                                <td>
                                    <b>Whatsapp</b> <br>
                                    <p>+216 {{ $infos->telephone ?? ''}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="icon-form-contact"><i class="fa-brands fa-facebook"></i></td>
                                <td>
                                    <b>Facebook</b> <br>
                                    <p> <a href="{{ $infos->facebook ?? '#'}}" target="_blank">{{ $infos->facebook ?? ''}}</a></p>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>

            <br><br>
            <div>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2791.6338128168068!2d-73.55721585452555!3d45.59790562605422!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cc91f702e0f8be9%3A0xe3efd9737b574487!2sClinique%20Dentaire%20Dr.Nathalie%20Arpin%20%2C%20Dr.Alain%20Gemme%20et%20Dr.%20Am%C3%A9lie%20Arpin-Gemme!5e0!3m2!1sfr!2sca!4v1735712944236!5m2!1sfr!2sca"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" class="w-100"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>


@endsection
