</main>
<footer class="text-muted bg-dark">
    <div class="container">
    <p class="float-right">
        <a href="#">Back to top</a>
    </p>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil incidunt aliquam rerum ex repellat, earum quaerat maxime reiciendis vero eum nulla eaque totam at dolor amet facilis sequi temporibus modi.</p>
    </div>
</footer>
<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>


    <!-- Replace the existing AJAX code in your course view file with the following code -->

<script>
$(document).ready(function() {
    $("#search-input").on("input", function() {
        const searchQuery = $(this).val();
        if (searchQuery.length >= 3) { // Only fetch autocomplete if input is at least 3 characters
            $.ajax({
                url: '<?php echo base_url("course/getAutoComplete"); ?>',
                type: 'GET',
                data: { search: searchQuery },
                dataType: 'json',
                success: function(autocomplete) {
                    let autocompleteHtml = '';
                    autocomplete.forEach(function(item) {
                        autocompleteHtml += `<a href="${generateSearchUrl(item.title)}" class="autocomplete-item">${item.title}</a>`;
                    });
                    $("#autocomplete").html(autocompleteHtml);
                    $("#autocomplete").show();
                }
            });
        } else {
            $("#autocomplete").hide();
        }
    });

    // Hide suggestions when clicking outside the search input and autocomplete box
    $(document).on("click", function(event) {
        if (!$(event.target).closest("#search-input, #autocomplete").length) {
            $("#autocomplete").hide();
        }
    });
    // Enable popover
    $('[data-toggle="popover"]').popover({
        html: true,
        content: function() {
            // Load cart items
            return $('.popover-cart-content').html();
        }
    });

    // Show/hide cart popover on hover
    $('.popover-cart-content').hover(
        function() {
            $('[data-toggle="popover"]').popover('show');
        },
        function() {
            $('[data-toggle="popover"]').popover('hide');
        }
    );

    $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'POST',
            data: {},
            success: function(response) {
                $(this).closest('.cart-item').remove();
                // Reload the cart popover content
                $('.popover-cart-content').load('<?php echo base_url(); ?>course/removeFromCart');
                location.reload();
            }
        });
    });

    // create a new Pusher instance
    var pusher = new Pusher('dd9f330e7502a0f07780', {
        cluster: 'ap4'
    });

    // subscribe to the notifications channel
    var notificationsChannel = pusher.subscribe('notifications');

    // listen for new notifications
    notificationsChannel.bind('new_notification', function(data) {
        var notification = JSON.parse(data);
        
        if (notification.type == 'course_added') {
            $('.badge-danger').text(notification.data.count);
            alert('New notification: ' + notification.data.body);
        }
    });


});

function showAutocomplete(autocomplete) {
    var autocomplete = document.getElementById('autocomplete');
    autocomplete.innerHTML = ''; // Clear the previous suggestions

    // Create a list of clickable links for each suggestion
    for (var i = 0; i < autocomplete.length; i++) {
        var autocomplete = autocomplete[i];
        var link = document.createElement('a');
        link.href = generateSearchUrl(autocomplete.title);
        link.classList.add('autocomplete-item');
        link.innerHTML = autocomplete.title;
        autocomplete.appendChild(link);
    }

    autocomplete.style.display = 'block';
}

function generateSearchUrl(query) {
            var baseUrl = '<?php echo base_url(); ?>';
            return baseUrl + 'course/searchKeyword?search=' + encodeURIComponent(query);
}

$(document).ready(function() {
    let offset = <?php echo count($course); ?>;
    const limit = 4;
    let isLoadingData = false;
    let searchKeyword = '';

    // Get search keyword from the URL
    const urlParams = new URLSearchParams(window.location.search);
    searchKeyword = urlParams.get('search') || '';
   
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
            if (!isLoadingData) {
                isLoadingData = true;
                $.ajax({
                    url: '<?php echo base_url("course/loadMoreCourses"); ?>',
                    type: 'GET',
                    data: {
                        offset: offset,
                        limit: limit,
                        search: searchKeyword // Pass the search keyword
                    },
                    dataType: 'json',
                    success: function(courses) {
                        if (courses.length > 0) {
                            courses.forEach(function(course) {
                                const courseHtml = `
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                                    <a class='course_card' href="<?php echo base_url('course/courseDetail/'); ?>${course.course_id}">
                                        <div class="card">
                                            <img src="<?php echo base_url(); ?>assets/img/demo_img.png" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <h5 class="card-title">${course.title}</h5>
                                                <p class="card-text">${course.course_brief}</p>
                                            </div>
                                        </div>
                                    </a>
                                    </div>`;
                                $('.row').append(courseHtml);
                            });

                            // Update the offset for the next batch of data
                            offset += courses.length;
                        }
                        isLoadingData = false;
                    },
                    error: function() {
                        isLoadingData = false;
                    }
                });
            }
        }
    });
    
});

 // Function to save scroll position
 function saveScrollPosition() {
    history.replaceState({scrollPosition: window.scrollY}, '');
 }
  // Function to restore scroll position
function restoreScrollPosition() {
    if (history.state && history.state.scrollPosition) {
      window.scrollTo(0, history.state.scrollPosition);
    }
}
  // Save scroll position when the page is unloaded
window.addEventListener('beforeunload', saveScrollPosition);
  // Restore scroll position when the page is loaded
window.addEventListener('popstate', restoreScrollPosition);





</script>

</body>
</html>

