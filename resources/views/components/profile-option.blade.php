<div class="card-v border py-3 rounded">
    <div class="user-list-saide-bar mt-4 bg-white rounded-1">
        <ul class="m-0">
            <a href="{{ route('profile') }}" class="settings-link">
                <li class="list-unstyled border-0 text-dark my-2 py-3 px-3 d-flex align-items-center ">
                    <i class="fa-solid fa-user"></i>
                    <span class="px-2 d-block">
                        Modifier le profil
                    </span>
                </li>
            </a>
            <a href="{{ route('change_password') }}" class="settings-link">
                <li class="list-unstyled text-dark border-0 my-2 py-3 px-3 d-flex align-items-center ">
                    <i class="fa-solid fa-lock"></i>
                    <span class="px-2 d-block">
                        Changer le mot de passe
                    </span>
                </li>
            </a>
            <a href="{{ route('orders') }}" class="settings-link ">
                <li class="list-unstyled text-dark border-0 my-2 py-3 px-3 d-flex align-items-center ">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="px-2 d-block">
                        Orders
                    </span>
                </li>
            </a>
            <a href="{{ route('favoris') }}" class="settings-link ">
                <li class="list-unstyled text-dark border-0 my-2 py-3 px-3 d-flex align-items-center ">
                    <i class="fa-solid fa-heart"></i>
                    <span class="px-2 d-block">
                        Ma liste de favoris
                    </span>
                </li>
            </a>
            <a href="{{ route('front.guide') }}" class="settings-link ">
                <li class="list-unstyled text-dark border-0 my-2 py-3 px-3 d-flex align-items-center ">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span class="px-2 d-block">
            Mon Skin Guide
        </span>
                </li>
            </a>

            <a href="{{ route('logout') }}" class="settings-link ">
                <li
                    class="list-unstyled text-dark border-0 my-2 py-3 px-3 d-flex align-items-center rounded">
                    <i class="fa-solid fa-person-walking-arrow-right"></i>
                    <b class="text-danger">
                        Sign out
                    </b>
                    </span>
                </li>
            </a>
        </ul>
    </div>
</div>
