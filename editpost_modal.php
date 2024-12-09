<!-- Modal Structure for Editing a Post -->
<div class="modal" id="editPostModal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <span>Edit Post</span>
            <button class="modal-close" onclick="closeModal('editPostModal')">×</button>
        </div>
        <form id="editPostForm" method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="postTitle">Post Title</label>
                <input type="text" id="postTitle" name="title" placeholder="Enter your title" required>
            </div>
            <div class="form-group">
                <label for="postDescription">Description</label>
                <textarea id="postDescription" name="content" rows="4" placeholder="Write your description" required></textarea>
            </div>
            <div class="form-group">
                <label for="postCategory">Category</label>
                <select id="postCategory" name="category">
                    <option value="Technology">Technology</option>
                    <option value="Lifestyle">Lifestyle</option>
                    <option value="Travel">Travel</option>
                </select>
            </div>
            <div class="form-group">
                <label for="postImage">Image</label>
                <input type="file" id="postImage" name="image_url" accept="image/*">
                <div id="currentImage"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModal('editPostModal')">Cancel</button>
                <button type="submit" class="btn btn-submit">Save Changes</button>
            </div>
        </form>
    </div>
</div>