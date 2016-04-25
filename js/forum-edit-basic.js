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

        checkSEOList();

        runAgain();
    }

    function checkSEOList() {
        checkText();
    }

    function checkText() {



        el.pro.check_list.find('li').css('display', 'none');

        // title variable
        var title = s.trim(el.title.val());
        var titleWords = s.words(title);
        var titleLength = title.length;
        var titleOK = true;



        // keyword variables
        var keyword = s.trim(el.keyword.val());
        var keywordWords = s.words( keyword );




        // code begin

        pro('count-title-words').text( titleWords.length );

        // title
        if ( titleLength < 1 ) { // input title. No title provided.
            pro('input-title').show();
        }
        else {
            pro('input-title').hide();
            if ( titleWords.length < 8 ) { // Only few words are provided.
                pro('input-more-words-on-title').show();

            }
            else {
                //
            }
            if ( titleWords.length > 20 ) { // Too much words are provided.
                pro('input-less-words-on-title').show();

            }
            else {
                //
            }
        }





        // content
        var editor;
        var content;
        var $content;
        var contentWords;
        var contentWordsLength;
        if ( typeof tinymce != 'undefined' ) {
            editor = tinymce.activeEditor;
            contentOriginal = editor.getContent();
            content = contentOriginal;

            $content = $("<div>" + contentOriginal + "</div>");
            content = s.stripTags( content );
            //console.log('contnet: ' + content)
            content = s.trim( content );
            contentWords = s.words( content );
            contentWordsLength = contentWords.length;
            pro('count-content-words').text( contentWords.length );
            //console.log('content.length: ' + content.length );
            if ( content.length < 1 ) {
                pro('input-content').show();

            }
            else {
                pro('input-content').hide();
                // console.log(' content word count : ' + contentWords.length );
                if ( contentWordsLength < 150 ) {
                    pro('input-minimum-words-on-content').show();
                }
                else if ( contentWordsLength < 300 ) {
                    pro('input-more-words-on-content').show();
                }


                // h1 tag in content.

                var $h1 = $content.find('h1');
                var count_h1 = $h1.length;
                var count_h1_keyword = 0;
                if ( count_h1 == 0 ) {
                    pro('input-h1').show();
                }
                else {
                    $h1.each( function (index) {
                            var h1 = $(this).text();
                            if ( s.count( h1, keyword ) ) count_h1_keyword ++;
                        })
                        .promise()
                        .done( function() {
                            if ( count_h1_keyword == 0 ) {
                                pro('input-keyword-on-h1').show();
                            }
                        });
                }
            }
        }





        // keyword check begins

        // keyword length.
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
            if ( count_keyword_on_content < 2 ) pro('input-minimum-two-keyword-on-content').show();
            else {
                // 내용에 비례해서 키워드 출현의 많고 적음.
                if ( contentWordsLength > 50 ) {
                    var min = Math.round(contentWordsLength * 0.015);
                    var max = Math.round(contentWordsLength * 0.025);
                    // console.log(min);
                    if ( count_keyword_on_content < min ) {
                        pro('input-more-keyword-on-content').show();
                    }
                    if ( count_keyword_on_content > max ) {
                        pro('input-less-keyword-on-content').show();
                    }
                }
            }

            pro('min-count-keyword-on-content').show().text( min );
            pro('max-count-keyword-on-content').show().text( max );

            var j = contentWords.length;
            var count_keyword_on_content_begin = 0;
            if ( j > 5 ) j = 5;
            for ( var i = 0; i < j; i ++ ) {
                if ( s.count( contentWords[i], keyword ) ) count_keyword_on_content_begin ++;
            }
            if ( count_keyword_on_content_begin == 0 ) pro('input-keyword-on-content-begin').show();
            // console.log(count_keyword_on_content_begin);
        }

        // image
        var p = /<img/img;
        if ( p.test(contentOriginal) === false ) {
            pro('input-image').show();
        }
        else {
            var countGoodAlt = 0;
            $content.find('img').each(function(){
                var alt = $(this).prop('alt');
                if ( s.count( alt, keyword ) ) countGoodAlt ++;
                //console.log(alt);
            });
            // console.log('goodAlt: ' + countGoodAlt);
            if ( countGoodAlt == 0 ) {
                pro('input-keyword-on-image-alt').show();
            }
        }

    }
});
