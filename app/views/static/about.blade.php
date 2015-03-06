@extends('static.template')
@section('content')
  <h1>What is this website?</h1>
  <p class="lead"><em>Lebanese Blogs</em> is The best place to discover, read and organize the latest posts from Lebanon's top bloggers and authors. It's made of a vibrant community of passionate people who care and write about a wide variety of topics</p>

  <h2>What people are saying about Lebanese Blogs</h2>
  <ul class="criteria">
    <li>"Lebanon's Top Blog Aggregator" <a href="http://cloud961.com/flip-magazine/?bkname=new_year_2014#19"> -Cloud961 Magazine</a></li>
    <li>"A Great Website" <a href="http://blogbaladi.com/lebaneseblogs-com-version-3-0/"> -Blog Baladi</a></li>
    <li>"A Daily Must-Check!"<a href="http://ginosblog.com/2013/04/18/lebaneseblogs-com-a-daily-must-check/"> -Gino's Blog</a></li>
    <li>"Made a major impact on the Lebanese digital crowd" <a href="http://tech-ticker.com/lebanesblogs-com-an-interview-with-mustapha-hamoui/"> -Tech Ticker</a></li>
    <li>"The best resource on Lebanese cyber sphere" <a href="https://www.facebook.com/leila.k.hanna/posts/10151788108666471"> -Leila Khauli Hanna</a></li>
  </ul>

  <h2>How are blogs chosen?</h2>
  <p>In order to provide the best and most relevant experience to readers, blogs that are indexed in Lebanese Blogs should satisfy some criteria. The blog has to be related to Lebanon and the blogger has to have a track record sustainable blogging. Read more about these criteria in the {{link_to('/about/submit', '"submit"')}} section</p>
  <p>If you think your blog belongs here and satisfies the criteria, what are you waiting for? {{link_to('/about/submit', 'Submit It!')}}</p>

  <h2>More Questions?</h2>
  <p>Find answers in our {{link_to('/about/faq', 'FAQ section')}}</p>
@stop
