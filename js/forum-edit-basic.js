jQuery( function($) {
    var timerPro = 0;
    var intervalRun = 500;
    $('.begin-pro').click(beginPro);
    $('.end-pro').click(endPro);

    beginPro();

    var $box = $('#post-new');
    var $pro = $('section#pro');
    var el = {
        box : $box,
        form : $box.find('form'),
        title : $box.find('[name="title"]'),
        keyword : $pro.find('[name="keyword"]'),
        pro : {
            check_list : $pro.find('.check-list'),
            status : $pro.find('.status')
        },
        p : function( cls ) {
            return $pro.find( '.' + cls );
        }
    };
    var seo = {};
    seo.rates = {};
    seo.rate = function ( lv ) {
        if ( typeof seo.rates[lv] == 'undefined' ) seo.rates[lv] = 1;
        else seo.rates[lv] ++;

        console.log( seo.rates );
    };
    seo.getRate = function () {

    };
    seo.showRateResult = function () {
        el.pro.status.find('li').hide();
        el.pro.status.find('.' + seo.getRate()).show();
    };
    function pro( cls ) {
        return el.p( cls );
    }
    function beginPro() {
        timerPro = setTimeout(runPro, intervalRun);
        $('.begin-pro').hide();
        $('.end-pro').show();
    }
    function endPro() {
        timerPro = 0;
        $('.begin-pro').show();
        $('.end-pro').hide();
    }
    function runAgain() {
        if ( timerPro ) setTimeout(runPro, intervalRun);
    }
    function runPro() {
        //console.log( 'run' );

        seo.rates = {};

        checkSEOList();


        seo.showRateResult();

        runAgain();
    }

    function checkSEOList() {
        checkText();
    }

    function checkText() {



        el.pro.check_list.find('li').css('display', 'none');

        var title = s.trim(el.title.val());
        var titleWords = s.words(title);
        var titleLength = title.length;
        var titleOK = true;

        pro('count-title-words').text( titleWords.length );

        // title
        if ( titleLength < 1 ) { // input title. No title provided.
            pro('input-title').show();
            seo.rate('worst');
        }
        else {
            pro('input-title').hide();
            if ( titleWords.length < 8 ) { // Only few words are provided.
                pro('input-more-words-on-title').show();
                seo.rate('worse');
            }
            else {
                //
            }
            if ( titleWords.length > 20 ) { // Too much words are provided.
                pro('input-less-words-on-title').show();
                seo.rate('worse');
            }
            else {
                //
            }
        }





        // content
        var editor;
        var content;
        var contentWords;
        var contentWordsLength;
        if ( typeof tinymce != 'undefined' ) {
            editor = tinymce.activeEditor;
            contentOriginal = editor.getContent();
            content = s.stripTags( content );
            content = s.trim( content );
            contentWords = s.words( content );
            contentWordsLength = contentWords.length;
            pro('count-content-words').text( contentWords.length );
            if ( content.length < 1 ) {
                pro('input-content').show();
                seo.rate('worst');
            }
            else {
                pro('input-content').hide();
                // console.log(' content word count : ' + contentWords.length );
                if ( contentWordsLength < 300 ) {
                    pro('input-more-words-on-content').show();
                }
                else {
                    //
                }
            }
        }


        // keyword
        var keyword = s.trim(el.keyword.val());
        var keywordWords = s.words( keyword );

        if ( keyword.length < 2 ) {
            pro('input-more-words-on-keyword').show();
            //
        }
        else {
            if ( keywordWords.length > 2 ) {
                pro('input-less-words-on-keyword').show();
            }
            else {
                //
            }
        }


        // title & keyword
        if ( title && keyword ) {
            var count_keyword_on_title = s.count( title, keyword);
            pro('count-keyword-on-title').text(count_keyword_on_title);
            if ( count_keyword_on_title > 2 ) {
                pro('input-less-keyword-on-title').show();
            }
            else if ( count_keyword_on_title < 1 ) {
                pro('input-keyword-on-title').show();
                pro('input-less-keyword-on-title').hide();
            }
            else {
                pro('input-less-keyword-on-title').hide();
            }
        }


        // content & keyword
        if ( content && keyword ) {
            var count_keyword_on_content = s.count( content, keyword );
            pro('count-keyword-on-content').text( count_keyword_on_content );
            if ( count_keyword_on_content < 2 ) pro('input-minimum-two-keyword-on-content');
            var min = Math.round(contentWordsLength * 0.015);
            var max = Math.round(contentWordsLength * 0.025);
            console.log(min);
            if ( count_keyword_on_content < min ) {
                pro('input-more-keyword-on-content').show();
            }
            if ( count_keyword_on_content > max ) {
                pro('input-less-keyword-on-content').show();
            }
            pro('min-count-keyword-on-content').show().text( min );
            pro('max-count-keyword-on-content').show().text( max );
        }

        // image
        var p = /<img/img;
        if ( p.test(contentOriginal) === false ) {
            pro('input-image').show();
        }
        else {
            var countGoodAlt = 0;
            $(contentOriginal).find('img').each(function(){
                var alt = $(this).prop('alt');
                if ( s.count( alt, keyword ) ) countGoodAlt ++;
                //console.log(alt);
            });
            console.log('goodAlt: ' + countGoodAlt);
            if ( countGoodAlt == 0 ) {
                pro('input-keyword-on-image-alt').show();
            }
        }

    }
});