<div class="container my-5 d-flex align-items-center">
    <livewire:breakpoints />
    <div class="row align-items-start text-center">
        <h1 class="display-5 fw-semibold lh-1 text-primary pb-4">
            What Our Customers Say
        </h1>
        <p class="lead col-md-8 mx-auto text-secondary">
            Hear from satisfied customers across Sri Lanka who have experienced the reliability, transparency, and
            convenience of our vehicle history and maintenance system. Their stories highlight the real impact we’ve
            made in helping individuals and businesses make smarter, safer decisions.
        </p>

        <div class="mt-5 mx-auto">
            <div id="customerSay" class="carousel slide " data-bs-ride="carousel">
                <div class="carousel-inner">
                    @windowWidthLessThan(640)
                        @foreach ($reviews as $review)
                            <div @class(['carousel-item', 'active' => $loop->first])>
                                <div class="row g-4 mx-auto">
                                    <div class="col col-md-6 col-lg-4">
                                        <div
                                            class="text-start p-4 border border-dark border-1 d-flex align-items-start flex-column h-100">
                                            <div class="col-3 ms-n3">
                                                <img class="pb-3" src="icons/logo-1.png" alt="login-icon" width="100%">
                                            </div>
                                            <div>
                                                <p class="text-secondary lead">"{{ $review['description'] }}"</p>
                                            </div>
                                            <div class="mt-auto">
                                                <div class="row pt-2">
                                                    <div class="col-3">
                                                        <img src="icons/man.png" alt="profile" width="100%">
                                                    </div>
                                                    <div class="col-9">
                                                        <h5 class="text-dark">{{ $review['name'] }}</h5>
                                                        <p>{{ $review['designation'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @windowWidthBetween(639, 992)
                        @foreach ($reviews as $index => $review)
                            @if ($index % 2 == 0)
                                <div class="carousel-item @if ($index == 0) active @endif">
                                    <div class="row g-4 mx-auto">
                            @endif
                                        <div class="col col-md-6 col-lg-4">
                                            <div
                                                class="text-start p-4 border border-dark border-1 d-flex align-items-start flex-column h-100">
                                                <div class="col-3 ms-n3">
                                                    <img class="pb-3" src="icons/logo-1.png" alt="login-icon" width="100%">
                                                </div>
                                                <div>
                                                    <p class="text-secondary lead">"{{ $review['description'] }}"</p>
                                                </div>
                                                <div class="mt-auto">
                                                    <div class="row pt-2">
                                                        <div class="col-3">
                                                            <img src="icons/man.png" alt="profile" width="100%">
                                                        </div>
                                                        <div class="col-9">
                                                            <h5 class="text-dark">{{ $review['name'] }}</h5>
                                                            <p>{{ $review['designation'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            @if ($index % 2 == 1 || $index == count($reviews) - 1)
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    @windowWidthGreaterThan(991)
                        @foreach ($reviews as $index => $review)
                            @if ($index % 3 == 0)
                                <div class="carousel-item @if ($index == 0) active @endif">
                                    <div class="row g-4 mx-auto">
                            @endif
                                        <div class="col col-md-6 col-lg-4">
                                            <div
                                                class="text-start p-4 border border-dark border-1 d-flex align-items-start flex-column h-100">
                                                <div class="col-3 ms-n3">
                                                    <img class="pb-3" src="icons/logo-1.png" alt="login-icon" width="100%">
                                                </div>
                                                <div>
                                                    <p class="text-secondary lead">"{{ $review['description'] }}"</p>
                                                </div>
                                                <div class="mt-auto">
                                                    <div class="row pt-2">
                                                        <div class="col-3">
                                                            <img src="icons/man.png" alt="profile" width="100%">
                                                        </div>
                                                        <div class="col-9">
                                                            <h5 class="text-dark">{{ $review['name'] }}</h5>
                                                            <p>{{ $review['designation'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            @if ($index % 3 == 2 || $index == count($reviews) - 1)
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
