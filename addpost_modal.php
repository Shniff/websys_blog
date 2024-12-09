<!-- Modal Structure for Creating a Post -->
<div class="modal" id="addPostModal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <span>Create a Post</span>
            <button class="modal-close" onclick="closeAddPostModal()">×</button>
        </div>
        <form method="POST" action="app/posts/add_post.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="addPostTitle">Post Title</label>
                <input type="text" id="addPostTitle" name="title" placeholder="Enter your title" required>
            </div>
            <div class="form-group">
                <label for="addPostDescription">Description</label>
                <textarea id="addPostDescription" name="content" rows="4" placeholder="Write your description" required></textarea>
            </div>
            <div class="form-group">
                <label for="addPostImage">Image</label>
                <input type="file" id="addPostImage" name="image_url" accept="image/*">
            </div>
            <div class="form-group">
                <label for="addPostCategory">Category</label>
                <select id="addPostCategory" name="category">
                    <option value="Technology">Technology</option>
                    <option value="Lifestyle">Lifestyle</option>
                    <option value="Travel">Travel</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeAddPostModal()">Cancel</button>
                <button type="submit" class="btn btn-submit">Post</button>
            </div>
        </form>
    </div>
</div>

<!-- Script to open and close the "Add Post" modal -->
<script>
    // Open the Add Post Modal
    function openAddPostModal() {
        const modal = document.getElementById("addPostModal");
        modal.style.display = "flex";
    }

    // Close the Add Post Modal
    function closeAddPostModal() {
        const modal = document.getElementById("addPostModal");
        modal.style.display = "none";
    }

    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        const modal = document.getElementById("addPostModal");
        if (event.target === modal) {
            closeAddPostModal();
        }
    };
</script>

<style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    .modal-content {
        background: #121212;
        padding: 20px;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1.2em;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5em;
        cursor: pointer;
    }
</style>
