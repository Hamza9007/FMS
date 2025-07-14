// this message auto hide after 5 seconds
   setTimeout(function() {
       document.querySelector('.alert').remove();
   }, 5000);


   function handlePOFileSelect(input, id) {
    const label = document.getElementById('chooseLabel_' + id);
    const fileNameSpan = document.getElementById('fileNameDisplay_' + id);

    if (input.files.length > 0) {
      fileNameSpan.textContent = input.files[0].name;
      fileNameSpan.style.display = 'inline-block';
      label.style.display = 'none'; // Hide choose file label
    }
  }

  function handleInvoiceSelect(input, id) {
    const label = document.getElementById('chooseInvoiceLabel_' + id);
    const fileNameSpan = document.getElementById('invoiceNameDisplay_' + id);

    if (input.files.length > 0) {
      fileNameSpan.textContent = input.files[0].name;
      fileNameSpan.style.display = 'inline-block';
      label.style.display = 'none';
    }
  }

  function handleQuotationSelect(input, id) {
    const label = document.getElementById('chooseQuotationLabel_' + id);
    const fileName = document.getElementById('quotationFileName_' + id);

    if (input.files.length > 0) {
      fileName.textContent = input.files[0].name;
      fileName.style.display = 'inline-block';
      label.style.display = 'none';
    }
  }

  