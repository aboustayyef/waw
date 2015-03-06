    </div> <!-- / siteWrapper -->
    <div id="footer">
      <!-- <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->

      {{-- Minify and combine JS assets later  --}}

      @if (User::signedIn() == true)
        <script>
          lbApp.signedIn = true;
        </script>
      @else
        <script>
          lbApp.signedIn = false;
        </script>
      @endif
      <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
      <script src ="{{asset('js/lebaneseblogs.min.js?v=161166778')}}"></script>

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

      <!-- Statcounter -->
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
    </div>
</body>
</html>
