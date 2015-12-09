<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

<!--Font Awesome CSS-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<!-- JQuery -->
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>

<!-- Bootstrap JavaScript (required JQuery) -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>

<!-- Logboat CSS -->
<link rel="stylesheet" href="<?= getBaseUrl(); ?>styles.css?v=0.3">

<!--- Chart JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>

<!--Table Sorter-->
<style>
    #getAllTable thead tr th.tablesorter-headerDesc div:hover:after,
    #getAllTable thead tr th.tablesorter-headerAsc div:hover:after,
    #getAllTable thead tr th.tablesorter-headerUnSorted div:hover:after {
      font-family: FontAwesome;
    }
    #getAllTable thead tr th.tablesorter-headerUnSorted div:hover:after {
      content: "\00a0\f0dc";
    }
    #getAllTable thead tr th.tablesorter-headerDesc div:hover:after {
      content: "\00a0\f0de";
    }
    #getAllTable thead tr th.tablesorter-headerAsc div:hover:after {
      content: "\00a0\f0dd";
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.24.6/js/jquery.tablesorter.min.js"></script>