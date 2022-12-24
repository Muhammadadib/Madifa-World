<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madifa World</title>
    <?php 
        require_once "./pages/components/dependenciesIncluder.php";
        addHeaderNavDependencies();
        dependenciesGenerator([], ["../style/list.css"]);
    ?>
</head>
<body>
    <?php 
        require_once "./pages/components/headernav.php";
    ?>

    <div class="contents">
        <h1 id="user-title">User List</h1>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody id="user_data"></tbody>
        </table>
        <div id="pagination"></div>
    </div>

    <script>
        function loadData(page = 1) {
            let request = new XMLHttpRequest();

            request.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    let response = JSON.parse(request.responseText);

                    // Table data
                    let tableHTML = "";
                    
                    if (response.data.length > 0) {
                        response.data.forEach(user => {
                            tableHTML += "<tr>";
                            tableHTML += "<td>" + user.username + "</td>";
                            tableHTML += "<td>" + user.email + "</td>";
                            tableHTML += "</tr>";
                        });
                    } else {
                        tableHTML = "<tr><td colspan='2'>No Users Found</td></tr>"
                    }

                    document.getElementById("user_data").innerHTML = tableHTML;

                    // Pagination links
                    let paginationHTML = "";
                    if (response.data.length > 0) {
                        if (page == 1) {
                            paginationHTML += "<a class='pagination-link active' href='javascript:loadData(1)'>1</a>";
                        }
                        else if (page == response.totalPages && response.totalPages > 2) {
                            paginationHTML += "<a class='pagination-link' href='javascript:loadData(" + (page-1) + ")'>Previous</a>";
                            paginationHTML += "<a class='pagination-link' href='javascript:loadData(" + (page-2) + ")'>" + (page-2) + "</a>";
                        }
                        else {
                            paginationHTML += "<a class='pagination-link' href='javascript:loadData(" + (page-1) + ")'>Previous</a>";
                            paginationHTML += "<a class='pagination-link' href='javascript:loadData(" + (page-1) + ")'>" + (page-1) + "</a>";
                        }

                        if (page == 1 && response.totalPages > 2) {
                            paginationHTML += "<a class='pagination-link' href='javascript:loadData(" + (page+1) + ")'>" + (page+1) + "</a>";
                        }
                        else if (page == response.totalPages && response.totalPages > 2) {
                            paginationHTML += "<a class='pagination-link' href='javascript:loadData(" + (page-1) + ")'>" + (page-1) + "</a>";
                        }
                        else if (response.totalPages > 2) {
                            paginationHTML += "<a class='pagination-link active' href='javascript:loadData(" + (page) + ")'>" + (page) + "</a>";
                        }

                        if (page == response.totalPages && response.totalPages > 1) {
                            paginationHTML += "<a class='pagination-link active' href='javascript:loadData(" + (page) + ")'>" + (page) + "</a>";
                        }
                        else if (page == 1 && response.totalPages > 2) {
                            paginationHTML += "<a class='pagination-link' href='javascript:loadData(" + (page+2) + ")'>" + (page+2) + "</a>";
                            paginationHTML += "<a class='pagination-link' href='javascript:loadData(" + (page+1) + ")'>Next</a>";
                        }
                        else if (response.totalPages > 2) {
                            paginationHTML += "<a class='pagination-link' href='javascript:loadData(" + (page+1) + ")'>" + (page+1) + "</a>";
                            paginationHTML += "<a class='pagination-link' href='javascript:loadData(" + (page+1) + ")'>Next</a>";
                        }
                    }
                    
                    document.getElementById("pagination").innerHTML = paginationHTML;
                }
            }

            const params = new URLSearchParams({
                page: page
            });
            request.open("GET", "/index.php/api/users?" + params.toString());
            request.send();
        }

        loadData(1);
    </script>
</body>
</html>