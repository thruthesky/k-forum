# K-Forum

K-Forum is short for "Knowledge Forum".

# todo

* 큰 문제 발생.
    * 기존의 post Post Type 을 그대로 사용하는 것은 좋은데,
    * Routing 과 Main Loop 를 활용하려고 억지로 Rewrite 를 지정하여 Main Loop 를 사용하는데,
    * /qna 와 같이 1 차 segment 를 사용하지 않고 무조건 /forum/qna 와 같이 2차 segment 를 사용한다.
    * 그리고 Main Loop 를 억지로 사용하지 않는다.
    

* Version compatibility from 3.5 to 4.5
    * Make it from 3.0 to 4.5

* 디자인
    * 기존의 won-moon hueman 디자인을 많이 따온다.
        * 특히 광고, 방문자 통계, 필고 배너, 패밀리 사이트 광고를 따 넣는다.
         
    
* SEO
    * 블로그를 목록하고 블로그 포스팅 선택 할 수 있도록 한다.
    * OG 태그 직접 입력.
        * OG 타이틀 이미지 직접 선택. 업로드.
	* http://static.googleusercontent.com/media/www.google.co.kr/ko/kr/intl/ko/webmasters/docs/search-engine-optimization-starter-guide-ko.pdf
	* https://docs.google.com/document/d/1l5nBwK4ztgml0kUXvy73cmMroDfWifooddutvfk_tCU/edit
	* 키워드 사용 회수 카운트.
	* A 태그 한개 이상 삽입.
	
	
	
	
* Comment work
	* Comment list. ( need to create a whole comment function to display buttons )
	* Comment Create with file upload. ( need to create a whole comment function ) 
	* Comment Update with file upload. 

* File Uploading.

    - show images in media page properly.

* Post/Comment Voting.

* Forum management
	* forum category statistics
	* forum options
		* how many post will be listed in list view page.
		* templating for each forum category.
		* and other options.
        



# Features

* K-Forum uses the same idea of managing posts and comments exactly as how WordPress does. Which means even if you uninstall K-Forum plugin, you can still use the contents that were written by K-Forum.
* K-Forum lets theme developers to use k-forum template files.  



 

## K Forum Delete

When a k-forum category is deleted, it renames the category as "Deleted-[Original Forum Name]" and puts it out of the k-forum category.

We do it just because the user may delete a category mistakenly. If the user wants to delete the categor, he can delete it from the "Post" ==> "Category Menu".


## K Forum URL

* /fourm/qna ==> qna forum.
* /forum/qna/edit ==> new post form page under qna forum
* /qna ==> qna fourm. This will be redirected to /forum/qna. If bbPress has same slug, bbPress will take priority. So, bbPress qna forum will be display.


* 만약 글을 k forum 에서 작성했는데, Post 메뉴에서 글을 수정 할 때, 카테고리를 여러개 선택한다면,

    - 글 하나에 여러개의 카테고리가 선택된다.
    - 하지만 K forum 은 글 하나에 하나의 k forum 카테고리만 가지는 것으로 가정하기 때문에 올바르지 않은 결과가 나타날 수 있다.
    - 따라서 글 수정은 Post 메뉴에서 하지 않는다.



# K-Forum Internals

## Much the same as WordPress

For instance, if a k-forum comment is deleted, it saves into trash just like WordPress comment does.




# ETC

* 글 카테고리 메뉴에서 K-forum 을 삭제해도 다시 생성된다.

* 워드프레스의 글과 카테고리를 그대로 활용하므로

    * 글과 동작이 매우 유사하다.
    
        * 예를 들면, 최근 글을 목록하는 페이지가 있다면, 케이 포럼에 쓴 글이 나타난다. 
     
    * 글 및 카테고리 관리를 글 메뉴와 카테고리 메뉴에서 하면 된다.
    
        * 예를 들면, 카테고리 별 글 이동 같은 것이 있다.



# SEO

## 동작

SEO 를 했다면, 계속 SEO 를 하도록 쿠키에 저장하고, SEO 버튼을 닫았으면, 엮시 기억해서 SEO 를 펴쳐보이지 않도록 한다. 즉, 마지막 기억을 한다.
 

## 키워드

키워드를 bar 로 했을 경우, 영어에서 bar 와 bargain 은 완전히 다른 의미이다.
따라서 영어로하는 경우에는 키워드 매칭을 단어별로 분리를 해야 한다.
하지만 키워드를 '노래'로 했을 경우, 한국어에서 "노래"와 "노래하다"는 비슷한 의미로 가진다.
따라서 글 내용이 한글인 경우, 글 내용 비례하여 키워드 밀집도를 다지는 경우, 키워드를 단어별로 분리하지 않고 그냥 단어 속에 키워드가 포함되면 같이 카운트를 해야 한다.
 * 따라서 키워드는 최소한 한글 두자 이상이 되어야 한다.
 * 참고로 자바스크립트에서 한글 한 글자의 길이는  1 이다.



# XMLRPC

It is amazing that xmlrpc for php is still working and maintained actively.

https://github.com/gggeek/phpxmlrpc
http://gggeek.github.io/phpxmlrpc/
https://github.com/gggeek/phpxmlrpc/blob/master/doc/manual/phpxmlrpc_manual.adoc

github.com/thruthesky/x 의 etc/service/push_to_blog.php 를 열어서 기존의 코드를 네이버로 집어 넣는다.

완전한 별도의 플러그인으로 하나 더 만든다.

https://docs.google.com/document/d/1qjb7JBeGBh-VWFFRJxeJwGTSGo1SvtuvXSUJ75Hzl3g/edit#heading=h.cjwku3clyqxh



## 블로깅 동작 방식

* 관리자 설정에서 '블로그 설정'을 하지 않으면 자동으로 블로그 포스팅 옵션이 나타나지 않는다.
* 관리자 설정에서 '관리자만 블로그 등록 가능'에 체크하면, 관리자만 블로그 포스팅 할 수 있다.
* 글 쓰기 및 수정을 할 때, '블로그'를 선택해야 한다.
    * 처음 글 쓰기 할 때, 블로그를 선택하지 않고, 글 수정 할 때, 블로그를 선택해도 블로그에 글이 생성된다.
* 글 수정 시, '블로그 선택 옵션'에 글이 이미 등록된 블로그에는 체크가 미리된다.

