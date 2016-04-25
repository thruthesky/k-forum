# K-Forum

K-Forum is short for "Knowledge Forum".

# todo

* Version compatibility from 4.3.3 to 4.5
    * Make it from 3.0 to 4.5

* SEO
	* keywords, images, alt, slug, length of tile/comment, H1/H2, keyword in title/H1/H2, links in content, etc.

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

