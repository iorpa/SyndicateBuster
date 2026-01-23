        function openAddBatchModal() {
            console.log("Opening Add Batch Modal");
            document.getElementById('addBatchModal').style.display = 'flex';
        }
        
        function openAddCommodityModal() {
            console.log("Opening Add Commodity Modal");
            document.getElementById('addCommodityModal').style.display = 'flex';
        }
        
        function closeModal(modalId) {
            console.log("Closing modal: " + modalId);
            document.getElementById(modalId).style.display = 'none';
        }
        
    
       