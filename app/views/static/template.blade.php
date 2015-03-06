<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Lebanese Blogs</title>
  <link rel="stylesheet" href="{{asset('css/static.css')}}">
</head>


<body>

<div id="pageWrapper">
  <header>
    <div class="inner">
    <a href="{{URL::to('/posts/all')}}"><div id="logo">
        Lebanese Blogs;
      </div></a>
    </div>
  </header>
  <div id="contentWrapper" class="inner">
      <aside>
        <ul>
          <a href="{{URL::to('/about')}}"
          <?php if (empty($slug)) {
            echo 'class="active" ';} ?>>
            <li>About</li>
          </a>
          <a href="{{URL::to('/about/faq')}}"
          <?php if ($slug == 'faq') {
            echo 'class="active" ';} ?>>
            <li>Frequently Asked Question</li>
          </a>
          <a href="{{URL::to('/about/submit')}}"
          <?php if ($slug == 'submit') {
            echo 'class="active" ';} ?>>
            <li>Submit Your Blog</li>
          </a>
          <a href="{{URL::to('/about/feedback')}}"
          <?php if ($slug == 'feedback') {
            echo 'class="active" ';} ?>>
            <li>Submit Feedback</li>
          </a>
          <a href="{{URL::to('/about/badge')}}"
          <?php if ($slug == 'badge') {
            echo 'class="active" ';} ?>>
            <li>Add our Badge to your blog</li>
          </a>
        </ul>
      </aside>

      <div class="content">
        @yield('content')
      </div>
    </div>
  <footer>
    <div class="inner">
      <?php echo 'Lebanese Blogs ' . date('Y') . ' &copy;'; ?>
    </div>
  </footer>
</div> <!-- /pageWrapper -->

          <!-- Start of Google Analytics Code -->
            <script>
              (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
              m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
              })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

              ga('create', 'UA-40418714-1', 'lebaneseblogs.com');
              ga('require', 'displayfeatures');
              ga('send', 'pageview');
            </script>

        <!-- Start of StatCounter Code for Default Guide -->
            <script type="text/javascript">
            var sc_project=8489889;
            var sc_invisible=1;
            var sc_security="6ec3dc93";
            var scJsHost = (("https:" == document.location.protocol) ?
            "https://secure." : "http://www.");
            document.write("<sc"+"ript type='text/javascript' src='" +
            scJsHost +
            "statcounter.com/counter/counter.js'></"+"script>");</script>
            <noscript><div class="statcounter"><a title="web counter"
            href="http://statcounter.com/" target="_blank"><img
            class="statcounter"
            src="https://c.statcounter.com/8489889/0/6ec3dc93/1/"
            alt="web counter"></a></div></noscript>
        <!-- End of StatCounter Code for Default Guide -->
</body>
</html>
