// PERBAIKAN: Semua kode utama di dalam DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {
    // 1. Progress bar animation
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        setTimeout(() => {
            progressBar.classList.add('animated');
        }, 500);
    }

    // 2. Initialize like/dislike functionality untuk POSTINGAN
    initPostLikeDislike();

    // 3. Initialize post options
    initPostOptions();

    // 4. Initialize click outside handler
    initClickOutsideHandler();

    // 5. Initialize comment dialog events
    initCommentDialogEvents();

    // 6. Initialize lightbox events
    initLightboxEvents();
});

// ============================================
// FUNGSI UTAMA POSTINGAN
// ============================================

// FUNGSI: Initialize like/dislike untuk postingan
function initPostLikeDislike() {
    // Like functionality untuk postingan
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            handlePostLike(this);
        });
    });

    // Dislike functionality untuk postingan
    document.querySelectorAll('.dislike-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            handlePostDislike(this);
        });
    });
}

// FUNGSI: Handle like untuk postingan
function handlePostLike(button) {
    const icon = button.querySelector('i');
    const count = button.querySelector('span');

    if (button.classList.contains('liked')) {
        // Unlike
        button.classList.remove('liked');
        icon.classList.remove('fas');
        icon.classList.add('far');
        if (count) {
            const currentCount = parseInt(count.textContent) || 0;
            count.textContent = Math.max(0, currentCount - 1);
        }
    } else {
        // Like
        button.classList.add('liked');
        icon.classList.remove('far');
        icon.classList.add('fas');
        if (count) {
            const currentCount = parseInt(count.textContent) || 0;
            count.textContent = currentCount + 1;
        }

        // Jika ada dislike aktif, hapus dislike
        const postActions = button.closest('.post-actions');
        if (postActions) {
            const dislikeBtn = postActions.querySelector('.dislike-btn');
            if (dislikeBtn && dislikeBtn.classList.contains('disliked')) {
                handlePostDislike(dislikeBtn, true); // true = hanya remove dislike
            }
        }
    }
}

// FUNGSI: Handle dislike untuk postingan
function handlePostDislike(button, removeOnly = false) {
    const icon = button.querySelector('i');
    const count = button.querySelector('span');

    if (button.classList.contains('disliked')) {
        // Undislike
        if (!removeOnly) {
            button.classList.remove('disliked');
            icon.classList.remove('fas');
            icon.classList.add('far');
            if (count) {
                const currentCount = parseInt(count.textContent) || 0;
                count.textContent = Math.max(0, currentCount - 1);
            }
        }
    } else {
        if (!removeOnly) {
            // Dislike
            button.classList.add('disliked');
            icon.classList.remove('far');
            icon.classList.add('fas');
            if (count) {
                const currentCount = parseInt(count.textContent) || 0;
                count.textContent = currentCount + 1;
            }

            // Jika ada like aktif, hapus like
            const postActions = button.closest('.post-actions');
            if (postActions) {
                const likeBtn = postActions.querySelector('.like-btn');
                if (likeBtn && likeBtn.classList.contains('liked')) {
                    handlePostLike(likeBtn);
                }
            }
        }
    }
}

// FUNGSI: Initialize post options
function initPostOptions() {
    document.querySelectorAll('.post-options button').forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            const menu = this.nextElementSibling;
            document.querySelectorAll('.post-options-menu').forEach(m => {
                if (m !== menu) m.classList.remove('active');
            });
            menu.classList.toggle('active');
        });
    });
}

// FUNGSI: Initialize click outside handler
function initClickOutsideHandler() {
    document.addEventListener('click', function (e) {
        document.querySelectorAll('.post-options-menu').forEach(menu => {
            if (!menu.contains(e.target) && !e.target.closest('.post-options button')) {
                menu.classList.remove('active');
            }
        });
    });
}

// FUNGSI: Bookmark post
function bookmarkPost(postId) {
    const toast = new bootstrap.Toast(document.getElementById('bookmarkToast'));
    toast.show();

    document.querySelectorAll('.post-options-menu').forEach(menu => {
        menu.classList.remove('active');
    });
}

// FUNGSI: Open report modal
function openReportModal(postId) {
    closeCommentDialog();
    document.querySelectorAll('.post-options-menu').forEach(menu => {
        menu.classList.remove('active');
    });
    const reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
    reportModal.show();
}

// ============================================
// FUNGSI COMMENT DIALOG
// ============================================

// Data untuk comments dan replies
const commentsData = {
    'post1': [
        {
            id: 'comment1_1',
            avatar: 'SR',
            name: 'Siti Rahayu',
            text: 'Wah menarik sekali! Apakah ada biaya pendaftaran untuk bootcamp ini?',
            time: '45 menit lalu',
            likes: 5,
            dislikes: 0,
            liked: false,
            disliked: false,
            replies: [
                {
                    id: 'reply1_1_1',
                    avatar: 'AD',
                    name: 'Admin UAD',
                    text: 'Bootcamp ini gratis untuk 100 peserta pertama yang lolos seleksi. Setelah itu ada subsidi 50% untuk alumni UAD.',
                    time: '30 menit lalu',
                    likes: 3,
                    dislikes: 1,
                    liked: false,
                    disliked: false,
                    replies: [
                        {
                            id: 'nested_reply1_1_1_1',
                            avatar: 'DI',
                            name: 'Deny Iqbal',
                            text: 'Terima kasih infonya Admin!',
                            time: '15 menit lalu',
                            likes: 2,
                            dislikes: 0,
                            liked: false,
                            disliked: false,
                            replies: []
                        }
                    ]
                },
                {
                    id: 'reply1_1_2',
                    avatar: 'BS',
                    name: 'Budi Santoso',
                    text: 'Bagus juga gratis untuk peserta pertama. Semoga bisa lolos seleksi!',
                    time: '15 menit lalu',
                    likes: 1,
                    dislikes: 0,
                    liked: false,
                    disliked: false,
                    replies: []
                }
            ]
        },
        {
            id: 'comment1_2',
            avatar: 'BS',
            name: 'Budi Santoso',
            text: 'Programnya berapa lama durasinya?',
            time: '30 menit lalu',
            likes: 3,
            dislikes: 1,
            liked: false,
            disliked: false,
            replies: [
                {
                    id: 'reply1_2_1',
                    avatar: 'AD',
                    name: 'Admin UAD',
                    text: 'Program berlangsung selama 3 bulan, dengan 2 sesi per minggu. Total 24 sesi pembelajaran.',
                    time: '25 menit lalu',
                    likes: 4,
                    dislikes: 0,
                    liked: false,
                    disliked: false,
                    replies: []
                }
            ]
        },
        {
            id: 'comment1_3',
            avatar: 'MW',
            name: 'Maya Wijaya',
            text: 'Saya tertarik! Apakah tersedia modul pembelajaran online?',
            time: '15 menit lalu',
            likes: 2,
            dislikes: 0,
            liked: false,
            disliked: false,
            replies: []
        }
    ],
    'post2': [
        {
            id: 'comment2_1',
            avatar: 'AR',
            name: 'Ahmad Rizki',
            text: 'Acara yang ditunggu-tunggu! Sudah daftar nih!',
            time: '2 jam lalu',
            likes: 8,
            dislikes: 2,
            liked: false,
            disliked: false,
            replies: [
                {
                    id: 'reply2_1_1',
                    avatar: 'DI',
                    name: 'Deny Iqbal',
                    text: 'Wah sama! Nanti ketemu di acaranya ya!',
                    time: '1 jam lalu',
                    likes: 3,
                    dislikes: 0,
                    liked: false,
                    disliked: false,
                    replies: [
                        {
                            id: 'nested_reply2_1_1_1',
                            avatar: 'AR',
                            name: 'Ahmad Rizki',
                            text: 'Pasti! Kita bikin reuni kecil-kecilan dulu sebelum acara utama.',
                            time: '45 menit lalu',
                            likes: 1,
                            dislikes: 0,
                            liked: false,
                            disliked: false,
                            replies: []
                        }
                    ]
                }
            ]
        },
        {
            id: 'comment2_2',
            avatar: 'RS',
            name: 'Rizki Setiawan',
            text: 'Bisa bawa keluarga tidak ya?',
            time: '1 jam lalu',
            likes: 4,
            dislikes: 1,
            liked: false,
            disliked: false,
            replies: [
                {
                    id: 'reply2_2_1',
                    avatar: 'AD',
                    name: 'Admin UAD',
                    text: 'Bisa membawa keluarga maksimal 2 orang dengan pendaftaran terpisah. Silakan daftarkan keluarga di form pendaftaran.',
                    time: '45 menit lalu',
                    likes: 5,
                    dislikes: 0,
                    liked: false,
                    disliked: false,
                    replies: []
                }
            ]
        },
        {
            id: 'comment2_3',
            avatar: 'DI',
            name: 'Deny Iqbal',
            text: 'Mantap! Siap datang dengan teman-teman angkatan 2018!',
            time: '30 menit lalu',
            likes: 7,
            dislikes: 1,
            liked: false,
            disliked: false,
            replies: []
        }
    ]
};

// Variabel global untuk comment dialog
let currentPostId = null;
let replyingTo = null;
let currentCommentId = null;

// FUNGSI: Initialize comment dialog events
function initCommentDialogEvents() {
    const commentDialog = document.getElementById('commentDialog');
    if (commentDialog) {
        commentDialog.addEventListener('click', function (e) {
            if (e.target === this) {
                closeCommentDialog();
            }
        });
    }

    const commentInput = document.getElementById('commentInput');
    if (commentInput) {
        commentInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendComment();
            }
        });
    }
}

// FUNGSI: Open comment dialog
function openCommentDialog(postId) {
    currentPostId = postId;
    replyingTo = null;
    const dialog = document.getElementById('commentDialog');
    const commentBody = document.getElementById('commentBody');

    // Load comments based on postId
    commentBody.innerHTML = generateCommentsWithReplies(postId);

    // Reset reply input
    const commentInput = document.getElementById('commentInput');
    commentInput.placeholder = "Tulis komentar...";

    dialog.classList.add('active');
    commentInput.focus();
}

// FUNGSI: Close comment dialog
function closeCommentDialog() {
    document.getElementById('commentDialog').classList.remove('active');
    document.getElementById('commentInput').value = '';
    currentPostId = null;
    replyingTo = null;
    currentCommentId = null;
}

// FUNGSI: Generate comments with replies
function generateCommentsWithReplies(postId) {
    const comments = commentsData[postId] || [];

    let html = '<div class="comments-container">';

    if (comments.length === 0) {
        html += '<p class="text-center text-muted">Belum ada komentar. Jadilah yang pertama berkomentar!</p>';
    } else {
        comments.forEach(comment => {
            html += generateCommentHTML(comment, 0);
        });
    }

    html += '</div>';
    return html;
}

// FUNGSI: Generate comment HTML
function generateCommentHTML(comment, level) {
    const isReply = level > 0;
    const containerClass = isReply ? 'nested-replies-container' : '';
    const itemClass = isReply ? 'reply-item' : 'comment-item';
    const avatarClass = isReply ? 'reply-avatar' : 'comment-avatar';
    const authorNameClass = isReply ? 'reply-author-name' : 'comment-author-name';
    const timeClass = isReply ? 'reply-time' : 'comment-time';
    const textClass = isReply ? 'reply-text' : 'comment-text';
    const actionsClass = isReply ? 'reply-actions' : 'comment-actions';
    const actionBtnClass = isReply ? 'reply-action-btn' : 'comment-action-btn';

    let html = `
                <div class="${itemClass} ${containerClass}" id="${comment.id}">
                    <div class="${isReply ? 'reply-header-section' : 'comment-header-section'}">
                        <div class="${avatarClass}">${comment.avatar}</div>
                        <div class="${isReply ? 'reply-author-info' : 'comment-author-info'}">
                            <div class="${authorNameClass}">${comment.name}</div>
                            <div class="${timeClass}">${comment.time}</div>
                        </div>
                    </div>
                    <div class="${isReply ? '' : 'comment-content-section'}">
                        <div class="${textClass}">${comment.text}</div>
                    </div>
                    <div class="${actionsClass}">
                        <button class="${actionBtnClass} ${comment.liked ? 'liked' : ''}" onclick="likeComment('${comment.id}')">
                            <i class="${comment.liked ? 'fas' : 'far'} fa-heart"></i> <span class="like-count">${comment.likes}</span>
                        </button>
                        <button class="${actionBtnClass} ${comment.disliked ? 'disliked' : ''}" onclick="dislikeComment('${comment.id}')">
                            <i class="${comment.disliked ? 'fas' : 'far'} fa-thumbs-down"></i> <span class="dislike-count">${comment.dislikes}</span>
                        </button>
                        <button class="${actionBtnClass} reply-btn" onclick="showReplyInput('${comment.id}')">
                            <i class="fas fa-reply"></i> Balas
                        </button>
            `;

    // Tambahkan tombol report untuk semua komentar dan reply
    html += `
                        <button class="reply-report-btn" onclick="openReportCommentModal('${comment.id}')">
                            <i class="fas fa-flag"></i> Laporkan
                        </button>
                    </div>
            `;

    // Tambahkan tombol toggle replies jika ada replies
    if (comment.replies && comment.replies.length > 0) {
        html += `
                    <button class="toggle-replies-btn" onclick="toggleReplies('${comment.id}')">
                        <i class="fas fa-chevron-down"></i>
                        <span>${comment.replies.length} balasan</span>
                    </button>
                    <div class="replies-container" id="replies-${comment.id}" style="display: none;">
                `;

        comment.replies.forEach(reply => {
            html += generateCommentHTML(reply, level + 1);
        });

        html += `</div>`;
    }

    // Add reply input container (hidden by default)
    html += `
                    <div class="reply-input-container" id="reply-input-${comment.id}">
                        <div class="reply-input-wrapper">
                            <div class="main-avatar">DI</div>
                            <textarea class="reply-input-field" placeholder="Tulis balasan..." id="reply-input-field-${comment.id}"></textarea>
                            <div class="reply-buttons">
                                <button class="reply-send-btn" onclick="sendReply('${comment.id}')">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                                <button class="reply-cancel-btn" onclick="hideReplyInput('${comment.id}')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

    return html;
}

// FUNGSI: Toggle replies visibility
function toggleReplies(commentId) {
    const repliesContainer = document.getElementById(`replies-${commentId}`);
    const toggleBtn = document.querySelector(`#${commentId} .toggle-replies-btn`);
    const icon = toggleBtn.querySelector('i');
    const textSpan = toggleBtn.querySelector('span');

    if (repliesContainer.style.display === 'none') {
        repliesContainer.style.display = 'block';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
        textSpan.textContent = textSpan.textContent.replace('balasan', 'sembunyikan');
    } else {
        repliesContainer.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
        textSpan.textContent = textSpan.textContent.replace('sembunyikan', 'balasan');
    }
}

// FUNGSI: Show reply input
function showReplyInput(commentId) {
    // Hide all other reply inputs
    document.querySelectorAll('.reply-input-container').forEach(container => {
        container.classList.remove('active');
    });

    // Show the selected reply input
    const replyInput = document.getElementById(`reply-input-${commentId}`);
    replyInput.classList.add('active');

    // Set replying state
    replyingTo = commentId;

    // Update main input placeholder
    const commentInput = document.getElementById('commentInput');
    const commentElement = document.getElementById(commentId);
    const authorName = commentElement.querySelector('.comment-author-name, .reply-author-name').textContent;
    commentInput.placeholder = `Membalas ${authorName}...`;

    // Focus on reply input
    const replyInputField = document.getElementById(`reply-input-field-${commentId}`);
    replyInputField.focus();
}

// FUNGSI: Hide reply input
function hideReplyInput(commentId) {
    const replyInput = document.getElementById(`reply-input-${commentId}`);
    replyInput.classList.remove('active');

    // Clear reply input field
    const replyInputField = document.getElementById(`reply-input-field-${commentId}`);
    replyInputField.value = '';

    // Reset replying state
    replyingTo = null;

    // Reset main input placeholder
    const commentInput = document.getElementById('commentInput');
    commentInput.placeholder = "Tulis komentar...";
}

// FUNGSI: Send comment
function sendComment() {
    const commentInput = document.getElementById('commentInput');
    const commentText = commentInput.value.trim();

    if (commentText === '') {
        alert('Silakan tulis komentar terlebih dahulu.');
        return;
    }

    const commentBody = document.getElementById('commentBody');

    if (replyingTo) {
        // This is a reply to an existing comment
        sendReply(replyingTo);
    } else {
        // This is a new comment
        const newComment = {
            id: `comment${Date.now()}`,
            avatar: 'DI',
            name: 'Deny Iqbal',
            text: commentText,
            time: 'Baru saja',
            likes: 0,
            dislikes: 0,
            liked: false,
            disliked: false,
            replies: []
        };

        // Add to comments data
        if (!commentsData[currentPostId]) {
            commentsData[currentPostId] = [];
        }
        commentsData[currentPostId].push(newComment);

        // Reload comments
        commentBody.innerHTML = generateCommentsWithReplies(currentPostId);

        // Update comment count
        const commentBtn = document.querySelector(`#${currentPostId} .comment-btn span`);
        if (commentBtn) {
            const currentCount = parseInt(commentBtn.textContent) || 0;
            commentBtn.textContent = currentCount + 1;
        }
    }

    commentInput.value = '';
    replyingTo = null;

    // Scroll to the new content
    commentBody.scrollTop = commentBody.scrollHeight;
}

// FUNGSI: Send reply
function sendReply(commentId) {
    const replyInputField = document.getElementById(`reply-input-field-${commentId}`);
    const replyText = replyInputField.value.trim();

    if (replyText === '') {
        alert('Silakan tulis balasan terlebih dahulu.');
        return;
    }

    // Find the comment in the data
    const comment = findCommentInData(commentId);

    if (comment) {
        // Add the reply
        const newReply = {
            id: `reply${Date.now()}`,
            avatar: 'DI',
            name: 'Deny Iqbal',
            text: replyText,
            time: 'Baru saja',
            likes: 0,
            dislikes: 0,
            liked: false,
            disliked: false,
            replies: []
        };

        if (!comment.replies) {
            comment.replies = [];
        }
        comment.replies.push(newReply);

        // Reload comments
        const commentBody = document.getElementById('commentBody');
        commentBody.innerHTML = generateCommentsWithReplies(currentPostId);

        // Hide reply input
        hideReplyInput(commentId);

        // Update comment count
        const commentBtn = document.querySelector(`#${currentPostId} .comment-btn span`);
        if (commentBtn) {
            const currentCount = parseInt(commentBtn.textContent) || 0;
            commentBtn.textContent = currentCount + 1;
        }

        // Scroll to the new reply
        commentBody.scrollTop = commentBody.scrollHeight;
    }
}

// FUNGSI: Find comment in data
function findCommentInData(commentId, commentsArray = null) {
    if (!commentsArray) {
        commentsArray = commentsData[currentPostId];
    }

    for (const comment of commentsArray) {
        if (comment.id === commentId) {
            return comment;
        }

        if (comment.replies && comment.replies.length > 0) {
            const found = findCommentInData(commentId, comment.replies);
            if (found) return found;
        }
    }

    return null;
}

// FUNGSI: Like comment
function likeComment(commentId) {
    // Find the comment in the data
    const comment = findCommentInData(commentId);

    if (comment) {
        if (comment.liked) {
            // Unlike
            comment.likes -= 1;
            comment.liked = false;
        } else {
            // Like
            comment.likes += 1;
            comment.liked = true;

            // If was disliked, remove dislike
            if (comment.disliked) {
                comment.dislikes = Math.max(0, comment.dislikes - 1);
                comment.disliked = false;
            }
        }

        // Update the display
        const likeButton = document.querySelector(`#${commentId} .fa-heart`).closest('button');
        const likeCountElement = document.querySelector(`#${commentId} .like-count`);
        const dislikeButton = document.querySelector(`#${commentId} .fa-thumbs-down`).closest('button');
        const dislikeCountElement = document.querySelector(`#${commentId} .dislike-count`);

        if (likeButton && likeCountElement) {
            if (comment.liked) {
                likeButton.classList.add('liked');
                likeButton.querySelector('i').classList.remove('far');
                likeButton.querySelector('i').classList.add('fas');
            } else {
                likeButton.classList.remove('liked');
                likeButton.querySelector('i').classList.remove('fas');
                likeButton.querySelector('i').classList.add('far');
            }
            likeCountElement.textContent = comment.likes;
        }

        if (dislikeButton && dislikeCountElement && comment.disliked === false) {
            dislikeButton.classList.remove('disliked');
            dislikeButton.querySelector('i').classList.remove('fas');
            dislikeButton.querySelector('i').classList.add('far');
            dislikeCountElement.textContent = comment.dislikes;
        }
    }
}

// FUNGSI: Dislike comment
function dislikeComment(commentId) {
    // Find the comment in the data
    const comment = findCommentInData(commentId);

    if (comment) {
        if (comment.disliked) {
            // Undislike
            comment.dislikes -= 1;
            comment.disliked = false;
        } else {
            // Dislike
            comment.dislikes += 1;
            comment.disliked = true;

            // If was liked, remove like
            if (comment.liked) {
                comment.likes = Math.max(0, comment.likes - 1);
                comment.liked = false;
            }
        }

        // Update the display
        const dislikeButton = document.querySelector(`#${commentId} .fa-thumbs-down`).closest('button');
        const dislikeCountElement = document.querySelector(`#${commentId} .dislike-count`);
        const likeButton = document.querySelector(`#${commentId} .fa-heart`).closest('button');
        const likeCountElement = document.querySelector(`#${commentId} .like-count`);

        if (dislikeButton && dislikeCountElement) {
            if (comment.disliked) {
                dislikeButton.classList.add('disliked');
                dislikeButton.querySelector('i').classList.remove('far');
                dislikeButton.querySelector('i').classList.add('fas');
            } else {
                dislikeButton.classList.remove('disliked');
                dislikeButton.querySelector('i').classList.remove('fas');
                dislikeButton.querySelector('i').classList.add('far');
            }
            dislikeCountElement.textContent = comment.dislikes;
        }

        if (likeButton && likeCountElement && comment.liked === false) {
            likeButton.classList.remove('liked');
            likeButton.querySelector('i').classList.remove('fas');
            likeButton.querySelector('i').classList.add('far');
            likeCountElement.textContent = comment.likes;
        }
    }
}

// ============================================
// FUNGSI REPORT
// ============================================

// FUNGSI: Open report comment modal
function openReportCommentModal(commentId) {
    currentCommentId = commentId;
    const modal = new bootstrap.Modal(document.getElementById('reportCommentModal'));
    modal.show();
}

// FUNGSI: Submit comment report
function submitCommentReport() {
    const selectedReason = document.querySelector('input[name="reportCommentReason"]:checked');
    if (!selectedReason) {
        alert('Silakan pilih alasan pelaporan.');
        return;
    }

    const details = document.getElementById('commentReportDetails').value.trim();

    // In a real application, you would send this data to the server
    console.log('Comment report submitted:', {
        commentId: currentCommentId,
        reason: selectedReason.value,
        details: details,
        postId: currentPostId
    });

    const toast = new bootstrap.Toast(document.getElementById('commentReportToast'));
    toast.show();

    const modal = bootstrap.Modal.getInstance(document.getElementById('reportCommentModal'));
    modal.hide();

    // Reset form
    document.querySelectorAll('input[name="reportCommentReason"]').forEach(radio => {
        radio.checked = false;
    });
    document.getElementById('commentReportDetails').value = '';

    currentCommentId = null;
}

// FUNGSI: Improved report functionality
document.querySelectorAll('input[name="reportReason"]').forEach(radio => {
    radio.addEventListener('change', function () {
        // Hide all detail sections
        document.querySelectorAll('.report-reason-details').forEach(detail => {
            detail.classList.remove('active');
        });

        // Show the selected detail section
        const detailsId = this.value + 'Details';
        const detailsElement = document.getElementById(detailsId);
        if (detailsElement) {
            detailsElement.classList.add('active');
        }
    });
});

// FUNGSI: Submit report
function submitReport() {
    const selectedReason = document.querySelector('input[name="reportReason"]:checked');
    if (!selectedReason) {
        alert('Silakan pilih alasan pelaporan.');
        return;
    }

    let additionalInfo = '';
    const detailsElement = document.getElementById(selectedReason.value + 'Details');
    if (detailsElement && detailsElement.classList.contains('active')) {
        additionalInfo = detailsElement.querySelector('textarea').value.trim();
    }

    // In a real application, you would send this data to the server
    console.log('Report submitted:', {
        reason: selectedReason.value,
        additionalInfo: additionalInfo,
        postId: 'current-post-id'
    });

    const toast = new bootstrap.Toast(document.getElementById('reportToast'));
    toast.show();

    const reportModal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
    reportModal.hide();

    // Reset form
    document.querySelectorAll('input[name="reportReason"]').forEach(radio => {
        radio.checked = false;
    });
    document.querySelectorAll('.report-reason-details').forEach(detail => {
        detail.classList.remove('active');
        detail.querySelector('textarea').value = '';
    });
}

// ============================================
// FUNGSI LIGHTBOX
// ============================================

// Variabel lightbox
let currentLightboxImages = [];
let currentLightboxIndex = 0;

// FUNGSI: Initialize lightbox events
function initLightboxEvents() {
    // Keyboard navigation for lightbox
    document.addEventListener('keydown', function (e) {
        const lightbox = document.getElementById('lightbox');
        if (lightbox.classList.contains('active')) {
            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowLeft') {
                changeLightboxImage(-1);
            } else if (e.key === 'ArrowRight') {
                changeLightboxImage(1);
            }
        }
    });

    // Close lightbox when clicking outside the image
    const lightbox = document.getElementById('lightbox');
    if (lightbox) {
        lightbox.addEventListener('click', function (e) {
            if (e.target === this || e.target.classList.contains('lightbox-close')) {
                closeLightbox();
            }
        });
    }
}

// FUNGSI: Open lightbox
function openLightbox(postId, imageIndex) {
    // Get all images from the post
    const post = document.getElementById(postId);
    const images = post.querySelectorAll('img');
    currentLightboxImages = Array.from(images).map(img => img.src);
    currentLightboxIndex = imageIndex;

    // Show the lightbox with the selected image
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxCounter = document.getElementById('lightbox-counter');

    lightboxImg.src = currentLightboxImages[currentLightboxIndex];
    lightboxCounter.textContent = `${currentLightboxIndex + 1} / ${currentLightboxImages.length}`;
    lightbox.classList.add('active');

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

// FUNGSI: Close lightbox
function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.remove('active');

    // Restore body scroll
    document.body.style.overflow = '';
}

// FUNGSI: Change lightbox image
function changeLightboxImage(direction) {
    currentLightboxIndex += direction;

    // Wrap around if needed
    if (currentLightboxIndex < 0) {
        currentLightboxIndex = currentLightboxImages.length - 1;
    } else if (currentLightboxIndex >= currentLightboxImages.length) {
        currentLightboxIndex = 0;
    }

    // Update the image and counter
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxCounter = document.getElementById('lightbox-counter');

    lightboxImg.src = currentLightboxImages[currentLightboxIndex];
    lightboxCounter.textContent = `${currentLightboxIndex + 1} / ${currentLightboxImages.length}`;
}

// ============================================
// FUNGSI LAINNYA
// ============================================

// FUNGSI: Navigate to Leaderboard
function navigateToLeaderboard() {
    // In a real application, this would navigate to the leaderboard page
    alert('Mengarahkan ke halaman Leaderboard...');
    // window.location.href = '/leaderboard';
}