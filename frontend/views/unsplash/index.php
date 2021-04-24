<div>
    <div class="search-box">
        <div class="form-inline">
            <input type="text" value="" name="search" , id="search" class="form-control" autocomplete="off">
            <button onclick="search()" class="btn btn-primary">Search</button>
        </div>
    </div>

    <div class="list-box">
        <div id="gallery"></div>
    </div>
</div>

<script>
    function search() {
        const search = $("#search").val();

        if (!search) {
            return alert("Search no can't be empty");
        }

        const url = `/unsplash/search`;
        $.ajax({
            url,
            type: "POST",
            data: {
                search
            },
            success: function(data) {
                let strToRender = "<p>No results</p>";

                if (data.data.length > 0) {
                    let description;
                    strToRender = "";

                    data.data.forEach(photo => {
                        description = photo.description || photo.alt_description;
                        strToRender += buildBox(photo.urls.small, description, photo.id);
                    });
                }

                $("#gallery").html(strToRender);
            }
        });
    }

    function addFavorites(photoId) {
        const url = `/favorites/add?photoId=${photoId}`;
        $.ajax({
            url,
            type: "GET",
            success: function(data) {
                alert(data.message);
            }
        });
    }

    function buildBox(url, description, photoId) {
        return `<div class="box-photo">` +
            `<a target="_blank" href="${url}">` +
            `<img src="${url}" alt="photo" width="600" height="400">` +
            `</a>` +
            `<div class="description">${description}</div>` +
            `<button class="btn btn-success" onclick="addFavorites('${photoId}')">Add favorites</button>` +
            `</div>`;
    }
</script>