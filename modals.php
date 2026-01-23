
    <!-- Add Batch Modal -->
    <div id="addBatchModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Batch</h2>
                <button class="close-btn" onclick="closeModal('addBatchModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addBatchForm" method="POST" action="process_add_batch.php">
                    <div class="form-group">
                        <label class="form-label">Commodity</label>
                        <select id="commoditySelect" name="commodity" class="form-control" required>
                            <option value="">Select Commodity</option>
                            <?php
                            // Get commodities from database
                            $commodities_sql = "SELECT commodities_id, name FROM commodities ORDER BY name";
                            $commodities_result = $conn->query($commodities_sql);
                            while($row = $commodities_result->fetch_assoc()) {
                                echo '<option value="' . $row['commodities_id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Quantity (kg)</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Enter quantity" required min="0.01" step="0.01">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Price per kg (৳)</label>
                        <input type="number" id="price" name="unit_price" class="form-control" placeholder="Enter price" required step="0.01">
                        <small style="color: #666;">Current price cap: <span id="priceCapDisplay">৳ 0.00</span></small>
                        
                        <div id="priceWarning" class="price-warning">
                            <!-- Price warning will appear here -->
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input type="text" id="location" name="location" class="form-control" placeholder="Enter storage location" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Quality Grade (Optional)</label>
                        <select id="quality" name="quality" class="form-control">
                            <option value="">Select Grade</option>
                            <option value="premium">Premium</option>
                            <option value="standard">Standard</option>
                            <option value="economy">Economy</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-delete" onclick="closeModal('addBatchModal')">Cancel</button>
                <button type="button" class="btn btn-view" onclick="submitBatchForm()">Add Batch</button>
            </div>
        </div>
    </div>
    
    <!-- Add Commodity Modal -->
    <div id="addCommodityModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Commodity</h2>
                <button class="close-btn" onclick="closeModal('addCommodityModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addCommodityForm" method="POST" action="process_add_commodity.php">
                    <div class="form-group">
                        <label class="form-label">Commodity Name *</label>
                        <input type="text" id="commodityName" name="name" class="form-control" placeholder="e.g., Tomato, Chicken, Milk" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Unit Type *</label>
                        <select id="commodityUnit" name="unit_type" class="form-control" required>
                            <option value="">Select Unit</option>
                            <option value="kg">Kilogram (kg)</option>
                            <option value="liter">Liter</option>
                            <option value="piece">Piece</option>
                            <option value="bag">Bag (50kg)</option>
                            <option value="sack">Sack</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select id="commodityCategory" name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="grain">Grain</option>
                            <option value="vegetable">Vegetable</option>
                            <option value="fruit">Fruit</option>
                            <option value="protein">Protein</option>
                            <option value="dairy">Dairy</option>
                            <option value="oil">Oil & Fat</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Description (Optional)</label>
                        <textarea id="commodityDesc" name="description" class="form-control" rows="3" placeholder="Brief description..."></textarea>
                    </div>
                    
                    <div class="price-warning" style="background: #e8f5e9; color: #155724; display: block;">
                        <strong>Note:</strong> New commodities require admin approval.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-delete" onclick="closeModal('addCommodityModal')">Cancel</button>
                <button type="button" class="btn btn-view" onclick="submitCommodityForm()">Submit for Approval</button>
            </div>
        </div>
    </div>

