document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper('.home-top-slider', {
        pagination: {
            el: '.swiper-pagination',
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
})

jQuery(window).on('load', function () {
    jQuery('.orderby').select2();
})

jQuery(function ($) {
    $(window).on('scroll', function () {
        if ($(this).scrollTop() >= 150) {
            $('.header-wrapper').addClass('is-dark');
            $('header').addClass('site-header--stuck');
            setTimeout(() => {
                $('header').addClass('site-header--opening');
            }, 100)
        } else {
            $('.header-wrapper').removeClass('is-dark');
            $('header').removeClass('site-header--stuck');
            setTimeout(() => {
                $('header').removeClass('site-header--opening');
            }, 100)
        }
    });

    if ($('body').hasClass('home')) {
        let headertop = new Swiper('.notify-slider', {
            loop: true,
            autoplay: true,
        });

        window.matchMedia('(max-width : 767.98px').addEventListener('change', function (e) {
            if (e.matches) {
                headertop = new Swiper('.notify-slider', {
                    loop: true,
                    autoplay: true,
                });
            } else {
                headertop.destroy();
            }
        })
    }

    $('.header-toggle').on('click', function () {
        $(this).toggleClass('toggled');
        $('.header-menu').slideToggle(
            {
                start: function () {
                    $('.header-menu').css('display', 'flex')
                }
            }
        )
        if ($('header').hasClass('open')) {
            setTimeout(() => $('header').removeClass('open'), 300)
        } else {
            $('header').toggleClass('open');
        }
    })

    $('.filter-icon').on('click', function () {
        $('.shop-left').toggleClass('open');
    })

    let timeout;

    $('body').on('change', 'input.qty', function () {

        if (timeout !== undefined) {
            clearTimeout(timeout);
        }

        timeout = setTimeout(function () {
            $("[name='update_cart']").trigger("click");
        }, 1000); // 1 second delay, half a second (500) seems comfortable too

    });

    $('body').on('change', '#payment-method-select', function () {
        let value = $(this).find('option:checked').val();
        console.log($(`.wc_payment_methods input[value="${value}"]`), `.wc_payment_methods input[value="${value}"]`);
        $(`.wc_payment_methods input[value=${value}]`).click();
    })

    $('.header-search-icon').on('click', function (e) {
        e.preventDefault();
        $('.header-search-input').addClass('show');
    })

    if (window.matchMedia('(max-width: 767px)').matches) {
        $(".menu-item-has-children>a").one('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('opened').closest('li').find('.sub-menu').slideDown()
        })
    }

    $('body').on('init_checkout updated_checkout', function () {
        $('#payment-method-select').select2();
    })

    $('.sproduct-tab-title').on('click', function () {
        if ($(this).parent().hasClass('active')) {
            $(this).parent().removeClass('active');
            $(this).parent().find('.sproduct-tab-content').slideUp();
        } else {
            $('.sproduct-tab').removeClass('active');
            $('.sproduct-tab .sproduct-tab-content').slideUp();

            $(this).parent().addClass('active');
            $(this).parent().find('.sproduct-tab-content').slideDown();
        }
    })

    $('.header-search-close').on('click', function () {
        $('.header-search-input').removeClass('show');
    })

    $('.size-guide-opener').on('click', function () {
        $('#size-modal').addClass('show');
        $('html').addClass('overflow-hidden')
    })

    $('.modal').on('click', function (e) {
        if ($(e.target).closest('.modal-close').length || $(e.target).is('.modal')) {
            $(this).closest('.modal').removeClass('show');
            $('html').removeClass('overflow-hidden')
        }
    })

    $('.home-top').on('click', function () {
        $('.header-search-input').removeClass('show');
    })
})
