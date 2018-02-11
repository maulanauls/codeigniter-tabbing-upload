<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#information" role="tab" aria-controls="home" aria-selected="true">INFORMATION</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#documentation" role="tab" aria-controls="profile" aria-selected="false">DOCUMENTATION</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="result-tab" data-toggle="tab" href="#result" role="tab" aria-controls="result" aria-selected="false">RESULTS</a>
    </li>
</ul>
<form id="form-upload" method="post" enctype="multipart/form-data">
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="information" role="tabpanel" aria-labelledby="information-tab">
        <div class="form-group">
            <label for="firstname-val">firstname</label>
            <input type="text" class="form-control" id="firstname-val" name="firstname" placeholder="firstname">
            <span id="firstname" class="help-block"></span>
        </div>
        <div class="form-group">
            <label for="lastname-val">lastname</label>
            <input type="text" class="form-control" id="lastname-val" name="lastname" placeholder="lastname">
            <span id="lastname" class="help-block"></span>
        </div>
        <div class="form-group">
            <label for="email-val">email address</label>
            <input type="email" class="form-control" id="email-val" name="email" placeholder="name@example.com">
            <span id="email" class="help-block"></span>
        </div>
    </div>
    <div class="tab-pane fade" id="documentation" role="tabpanel" aria-labelledby="documentation-tab">
        <div class="form-group">
            <label for="title-val">title</label>
            <input type="text" name="title" class="form-control" id="title-val" placeholder="title">
            <span id="title" class="help-block"></span>
        </div>
        <div class="form-group">
            <label for="document-val">document</label>
            <input type="file" class="form-control-file" name="document" id="document-val">
            <span id="document" class="help-block"></span>
        </div>
        <div class="form-group">
            <button type="submit" id="btn-save" onclick="save_change()" class="btn btn-primary">submit</button>
        </div>
    </div>
    <div class="tab-pane fade" id="result" role="tabpanel" aria-labelledby="result-tab">
        <hr>
        <?php foreach($users as $list){ ?>
        <div class="card" style="width: 18rem;">
            <img class="card-img-top" src="<?=base_url('assets/upload-file');?>/<?=$list->file;?>" alt="<?=$list->title;?>">
            <div class="card-body">
                <h5 class="card-title"><?=$list->firstname;?>&nbsp;<?=$list->lastname;?></h5>
                <p class="card-text"><?=$list->title;?></p>
                <a href="#" class="btn btn-primary">go somewhere</a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
</form>
