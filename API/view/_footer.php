
<div class="footer">
    <small>
        &copy; <a href="mailto:stmatea@student.math.hr">Matea Stanišić</a><br />
    </small>
</div>

<script type="text/javascript">
    $("document").ready(function() {
        //pritiskom na naslov "vraćamo se na početnu stranicu" ~ hidamo sve s desne strane
        $('#page_name').on( "click", function() {
            var loc1 = window.location.pathname;
            var loc2 = {
                url : '?rt=index/index'
            };
            console.log(loc1);
            window.location.assign(loc1+loc2.url);
        });
    } )
</script>

</body>
</html>