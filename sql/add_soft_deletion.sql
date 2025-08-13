-- Add soft deletion column to employee table
ALTER TABLE `employee` ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_admin`;

-- Create index for better performance on soft deleted records
CREATE INDEX `idx_is_deleted` ON `employee` (`is_deleted`);

-- Update existing records to ensure they are not marked as deleted
UPDATE `employee` SET `is_deleted` = 0 WHERE `is_deleted` IS NULL;
