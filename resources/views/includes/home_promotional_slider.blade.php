@if(isset($sliders) && count($sliders) > 0)
<style>
/* Promotional Banner Slider Container */
.promo-slider-container {
    padding: 16px 0 24px 0;
    position: relative;
    width: 100%;
}
.promo-slider-viewport {
    position: relative;
    width: 100%;
    aspect-ratio: 2.5 / 1;
    border-radius: 14px;
    overflow: hidden;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    box-shadow: 0 2px 12px rgba(15,23,42,0.04);
}
@supports not (aspect-ratio: 2.5 / 1) {
    .promo-slider-viewport {
        height: 380px;
    }
}
.promo-slider-track {
    display: flex;
    width: 100%;
    height: 100%;
    transition: transform 0.4s ease;
    will-change: transform;
    margin: 0;
    padding: 0;
}
.promo-slide {
    flex: 0 0 100%;
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
    background: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
}
.promo-slide-link {
    display: block;
    width: 100%;
    height: 100%;
    text-decoration: none !important;
    position: relative;
    overflow: hidden;
}
.promo-slide-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    border: 0;
}

/* Fallback Card (when no banner image is provided) */
.promo-slide-fallback {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    padding: 36px 48px;
    width: 100%;
    height: 100%;
    background: #FFFFFF;
}
.promo-fallback-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #EFF6FF;
    border: 1px solid #BFDBFE;
    color: #2563EB;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.promo-fallback-title {
    font-size: 24px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 8px;
    line-height: 1.3;
}
.promo-fallback-desc {
    font-size: 14.5px;
    color: #64748B;
    line-height: 1.6;
    margin-bottom: 18px;
    max-width: 700px;
}
.promo-fallback-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #2563EB;
    color: #FFFFFF !important;
    font-weight: 700;
    font-size: 14px;
    padding: 10px 22px;
    border-radius: 8px;
    transition: all 0.2s ease;
}
.promo-fallback-btn:hover {
    background: #1D4ED8;
}

/* Navigation Arrow Buttons */
.promo-arrow-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    color: #0F172A;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(15,23,42,0.12);
    transition: all 0.2s ease;
    z-index: 10;
    outline: none !important;
    padding: 0;
}
.promo-arrow-btn:hover {
    background: #2563EB;
    color: #FFFFFF !important;
    border-color: #2563EB;
    transform: translateY(-50%) scale(1.05);
}
.promo-arrow-prev {
    left: 14px;
}
.promo-arrow-next {
    right: 14px;
}

/* Dot Indicators */
.promo-dots {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
}
.promo-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #CBD5E1;
    border: none;
    cursor: pointer;
    transition: all 0.25s ease;
    padding: 0;
    outline: none !important;
}
.promo-dot:hover {
    background: #94A3B8;
}
.promo-dot.active {
    width: 24px;
    border-radius: 10px;
    background: #2563EB;
}

/* Mobile Responsive */
@media (max-width: 767px) {
    .promo-slider-container {
        padding: 12px 0 18px 0;
    }
    .promo-slider-viewport {
        border-radius: 10px;
        aspect-ratio: 2.5 / 1;
    }
    @supports not (aspect-ratio: 2.5 / 1) {
        .promo-slider-viewport {
            height: 150px;
        }
    }
    .promo-arrow-btn {
        width: 32px;
        height: 32px;
        font-size: 11px;
    }
    .promo-arrow-prev {
        left: 8px;
    }
    .promo-arrow-next {
        right: 8px;
    }
    .promo-slide-fallback {
        padding: 16px 20px;
    }
    .promo-fallback-title {
        font-size: 16px;
    }
    .promo-fallback-desc {
        font-size: 12px;
        margin-bottom: 12px;
    }
    .promo-fallback-btn {
        padding: 6px 14px;
        font-size: 12px;
    }
}
</style>

<div class="promo-slider-section">
    <div class="container">
        <div class="promo-slider-container">
            <div class="promo-slider-viewport" id="promoSliderViewport">
                <div class="promo-slider-track" id="promoSliderTrack">
                    @foreach($sliders as $key => $slider)
                    @php
                        $targetUrl = (!empty($slider->slider_link) && $slider->slider_link !== '#') ? $slider->slider_link : url('/jobs');
                        $imgExists = false;
                        $imgSrc = '';
                        if (!empty($slider->slider_image)) {
                            if (file_exists(public_path('slider_images/' . $slider->slider_image))) {
                                $imgExists = true;
                                $imgSrc = asset('slider_images/' . $slider->slider_image);
                            } elseif (file_exists(public_path('slider_images/mid/' . $slider->slider_image))) {
                                $imgExists = true;
                                $imgSrc = asset('slider_images/mid/' . $slider->slider_image);
                            }
                        }
                    @endphp

                    <div class="promo-slide" data-slide-index="{{ $key }}">
                        @if($imgExists)
                            <a href="{{ $targetUrl }}" class="promo-slide-link" title="{{ $slider->slider_heading }}">
                                <img src="{{ $imgSrc }}" 
                                     alt="{{ $slider->slider_heading ?: __('Promotional Offer') }}" 
                                     class="promo-slide-img" 
                                     loading="{{ $key === 0 ? 'eager' : 'lazy' }}">
                            </a>
                        @else
                            <div class="promo-slide-fallback">
                                <span class="promo-fallback-badge">
                                    <i class="fa fa-bullhorn"></i> Special Announcement
                                </span>
                                <h2 class="promo-fallback-title">{{ $slider->slider_heading }}</h2>
                                @if(!empty($slider->slider_description))
                                <div class="promo-fallback-desc">
                                    {!! $slider->slider_description !!}
                                </div>
                                @endif
                                <a href="{{ $targetUrl }}" class="promo-fallback-btn">
                                    <span>{{ $slider->slider_link_text ?: __('Explore Now') }}</span>
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                @if(count($sliders) > 1)
                <!-- Navigation Arrows -->
                <button type="button" class="promo-arrow-btn promo-arrow-prev" id="promoPrevBtn" aria-label="Previous banner">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </button>
                <button type="button" class="promo-arrow-btn promo-arrow-next" id="promoNextBtn" aria-label="Next banner">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </button>
                @endif
            </div>

            @if(count($sliders) > 1)
            <!-- Dot Indicators -->
            <div class="promo-dots" id="promoDots">
                @foreach($sliders as $key => $slider)
                <button type="button" class="promo-dot {{ $key === 0 ? 'active' : '' }}" data-slide-to="{{ $key }}" aria-label="Slide {{ $key + 1 }}"></button>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

@if(count($sliders) > 1)
<script>
(function() {
    var track = document.getElementById('promoSliderTrack');
    var viewport = document.getElementById('promoSliderViewport');
    var prevBtn = document.getElementById('promoPrevBtn');
    var nextBtn = document.getElementById('promoNextBtn');
    var dotsContainer = document.getElementById('promoDots');
    
    if (!track) return;
    
    var dots = dotsContainer ? dotsContainer.querySelectorAll('.promo-dot') : [];
    var totalSlides = {{ count($sliders) }};
    var currentIndex = 0;
    var autoplayTimer = null;
    var AUTOPLAY_DELAY = 4500;

    function goToSlide(index) {
        if (index < 0) {
            index = totalSlides - 1;
        } else if (index >= totalSlides) {
            index = 0;
        }
        currentIndex = index;
        track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
        
        for (var i = 0; i < dots.length; i++) {
            if (i === currentIndex) {
                dots[i].classList.add('active');
            } else {
                dots[i].classList.remove('active');
            }
        }
    }

    function startAutoplay() {
        stopAutoplay();
        autoplayTimer = setInterval(function() {
            goToSlide(currentIndex + 1);
        }, AUTOPLAY_DELAY);
    }

    function stopAutoplay() {
        if (autoplayTimer) {
            clearInterval(autoplayTimer);
            autoplayTimer = null;
        }
    }

    function resetAutoplay() {
        stopAutoplay();
        startAutoplay();
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            goToSlide(currentIndex - 1);
            resetAutoplay();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            goToSlide(currentIndex + 1);
            resetAutoplay();
        });
    }

    if (dots && dots.length > 0) {
        for (var d = 0; d < dots.length; d++) {
            (function(dot) {
                dot.addEventListener('click', function(e) {
                    e.preventDefault();
                    var targetIdx = parseInt(this.getAttribute('data-slide-to'), 10);
                    if (!isNaN(targetIdx)) {
                        goToSlide(targetIdx);
                        resetAutoplay();
                    }
                });
            })(dots[d]);
        }
    }

    if (viewport) {
        viewport.addEventListener('mouseenter', stopAutoplay);
        viewport.addEventListener('mouseleave', startAutoplay);
        
        // Touch swipe support for mobile
        var touchStartX = 0;
        var touchEndX = 0;
        viewport.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        viewport.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            var diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 40) {
                if (diff > 0) {
                    goToSlide(currentIndex + 1);
                } else {
                    goToSlide(currentIndex - 1);
                }
                resetAutoplay();
            }
        }, { passive: true });
    }

    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoplay();
        } else {
            startAutoplay();
        }
    });

    startAutoplay();
})();
</script>
@endif
@endif
