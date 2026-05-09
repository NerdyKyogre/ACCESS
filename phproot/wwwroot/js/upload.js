const fileInput = document.getElementById('image');

fileInput.addEventListener('change', (event) => {
    const fileList = event.target.files;
    if (fileList.length > 0) {
        const fileName = fileList[0].name;
        document.querySelectorAll('#imgFileName').forEach(element => element.remove());
        nameTag = document.createElement('p');
        nameTag.id = 'imgFileName';
        nameTag.textContent = fileName;
        fileInput.after(nameTag);
    }
});
