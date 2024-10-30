<form id="imk_contact" method="post">
    <div class="imk-contact-message hide alert"></div>
    <div class="form-group">
        <label for="">Email<sup class="text-danger">*</sup></label>
        <input type="text" class="form-control" data-validation="email" name="emailId">
    </div>
    <div class="form-group">
        <label for="">Name<sup class="text-danger">*</sup></label>
        <input type="text" class="form-control" data-validation="required" name="firstName">
    </div>

    <div class="form-group">
        <label for="">Phone<sup class="text-danger">*</sup></label>
        <input type="text" class="form-control" data-validation="required" name="phone">
    </div>

    <div class="form-group">
        <label for="">Question<sup class="text-danger">*</sup></label>
        <textarea rows="4" class="form-control bg-gray" data-validation="required" name="question"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Send</button>
</form>