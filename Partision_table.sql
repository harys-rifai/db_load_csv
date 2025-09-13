-- Step 1: Rename the existing table
ALTER TABLE system_metrics RENAME TO system_metrics_old;

-- Step 2: Create the partitioned parent table
CREATE TABLE system_metrics (
    timestamp TIMESTAMP NOT NULL,
    hostname TEXT,
    environment TEXT,
    cpu_usage TEXT,
    memory_usage TEXT,
    disk_usage TEXT,
    network_usage TEXT,
    status TEXT,
    extra1 TEXT,
    extra2 TEXT,
    file_name TEXT,
    load_status TEXT,
    pgver TEXT
) PARTITION BY RANGE (timestamp);

-- Step 3: Create partitions for 2025
CREATE TABLE system_metrics_2025_q1 PARTITION OF system_metrics
FOR VALUES FROM ('2025-01-01') TO ('2025-04-01');

CREATE TABLE system_metrics_2025_q2 PARTITION OF system_metrics
FOR VALUES FROM ('2025-04-01') TO ('2025-07-01');

CREATE TABLE system_metrics_2025_q3 PARTITION OF system_metrics
FOR VALUES FROM ('2025-07-01') TO ('2025-10-01');

CREATE TABLE system_metrics_2025_q4 PARTITION OF system_metrics
FOR VALUES FROM ('2025-10-01') TO ('2026-01-01');

-- Step 4: Create partitions for 2026
CREATE TABLE system_metrics_2026_q1 PARTITION OF system_metrics
FOR VALUES FROM ('2026-01-01') TO ('2026-04-01');

CREATE TABLE system_metrics_2026_q2 PARTITION OF system_metrics
FOR VALUES FROM ('2026-04-01') TO ('2026-07-01');

CREATE TABLE system_metrics_2026_q3 PARTITION OF system_metrics
FOR VALUES FROM ('2026-07-01') TO ('2026-10-01');

CREATE TABLE system_metrics_2026_q4 PARTITION OF system_metrics
FOR VALUES FROM ('2026-10-01') TO ('2027-01-01');

-- Step 5: Migrate data from old table to new partitioned table
INSERT INTO system_metrics
SELECT * FROM system_metrics_old;

-- Step 6: Drop the old table (optional)
DROP TABLE system_metrics_old;
