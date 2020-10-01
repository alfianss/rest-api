<form action="#form_post" method="post" id="form_post">
    <div>
        <label for="title">Title</label>
        <input type="text" name="title" id="title" placeholder="Title">
    </div>
    <div>
        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="publish">Publish</option>
            <option value="draft">Draft</option>
        </select>
    </div>
    <div>
        <label for="content">Content</label>
        <input type="text" name="content" id="content" placeholder="Content">
    </div>
    <div>
        <input type="submit" name="submit" value="Save">
    </div>
</form>