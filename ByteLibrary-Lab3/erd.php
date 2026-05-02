<?php
require "includes/auth.php";
$activePage = "erd";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DB Design - ByteLibrary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@300;400;500&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* ── ERD PAGE EXTRAS ── */
    .lab-badge {
      display: inline-flex; align-items: center; gap: 8px;
      background: var(--cream); border: 1px solid var(--border);
      border-radius: 20px; padding: 4px 14px;
      font-family: 'DM Mono', monospace; font-size: 11px;
      letter-spacing: 1.5px; text-transform: uppercase; color: var(--muted);
      margin-bottom: 20px;
    }
    .lab-badge span { color: var(--accent); }

    .intro-card {
      background: var(--card); border: 1px solid var(--border);
      border-radius: 10px; padding: 22px 28px; margin-bottom: 28px;
      display: flex; gap: 20px; align-items: flex-start;
    }
    .intro-icon { font-size: 28px; flex-shrink: 0; margin-top: 2px; }
    .intro-title { font-family: 'DM Serif Display', serif; font-size: 17px; margin-bottom: 5px; }
    .intro-desc { font-size: 13px; color: var(--muted); line-height: 1.65; }

    /* ── TABLE STRUCTURE CARDS ── */
    .tables-grid {
      display: grid; grid-template-columns: repeat(3, 1fr);
      gap: 20px; margin-bottom: 32px;
    }
    .db-table-card {
      background: var(--card); border: 1px solid var(--border);
      border-radius: 10px; overflow: hidden;
    }
    .db-table-header {
      padding: 14px 18px;
      display: flex; align-items: center; gap: 10px;
      border-bottom: 1px solid var(--border);
    }
    .db-table-header.users    { background: #1a1714; }
    .db-table-header.books    { background: #2a1a10; }
    .db-table-header.borrowers{ background: #0f1f1a; }
    .db-table-header.loans    { background: #1a1020; }
    .db-table-name {
      font-family: 'DM Mono', monospace; font-size: 13px;
      font-weight: 500; color: var(--paper); letter-spacing: 1px;
      text-transform: uppercase;
    }
    .db-table-icon { font-size: 16px; }
    .db-table-body { padding: 0; }
    .db-field {
      display: flex; align-items: center;
      padding: 9px 18px;
      border-bottom: 1px solid var(--border);
      font-size: 13px; gap: 10px;
    }
    .db-field:last-child { border-bottom: none; }
    .db-field:hover { background: var(--cream); }
    .field-key {
      font-size: 10px; padding: 2px 6px;
      border-radius: 4px; font-family: 'DM Mono', monospace;
      font-weight: 600; flex-shrink: 0;
    }
    .pk  { background: #fef3e6; color: #b36b00; }
    .fk  { background: #e6eef8; color: #2a5ba0; }
    .field-name { font-family: 'DM Mono', monospace; font-size: 12px; color: var(--ink); flex: 1; }
    .field-type { font-family: 'DM Mono', monospace; font-size: 11px; color: var(--muted); }
    .field-constraint {
      font-size: 10px; font-family: 'DM Mono', monospace;
      color: var(--sage); background: #e6f2e6;
      padding: 1px 6px; border-radius: 3px;
    }

    /* ── ERD DIAGRAM ── */
    .erd-wrap {
      background: var(--card); border: 1px solid var(--border);
      border-radius: 10px; padding: 28px; margin-bottom: 32px;
      overflow-x: auto;
    }
    .erd-title {
      font-family: 'DM Serif Display', serif; font-size: 17px;
      margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
    }
    .erd-title::after {
      content: ''; flex: 1; height: 1px; background: var(--border);
    }
    .erd-svg-wrap { display: flex; justify-content: center; }

    /* ── DATA MAPPING TABLE ── */
    .mapping-wrap {
      background: var(--card); border: 1px solid var(--border);
      border-radius: 10px; overflow: hidden; margin-bottom: 32px;
    }
    .mapping-header {
      padding: 16px 20px; border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
    }
    .mapping-header-title { font-family: 'DM Serif Display', serif; font-size: 17px; }
    .mapping-header-sub { font-size: 12px; color: var(--muted); font-family: 'DM Mono', monospace; letter-spacing: 0.5px; }
    .mapping-table th { background: var(--cream); }
    .mapping-table th:first-child  { color: var(--accent); }
    .mapping-table .page-col  { font-weight: 600; color: var(--ink); }
    .mapping-table .field-col { font-family: 'DM Mono', monospace; font-size: 12px; }
    .mapping-table .type-col  { font-family: 'DM Mono', monospace; font-size: 12px; color: var(--muted); }
    .mapping-table .table-col {
      font-family: 'DM Mono', monospace; font-size: 12px;
      color: var(--accent); font-weight: 600;
    }
    .row-group td:first-child { border-left: 3px solid var(--accent); }
    .row-group-books td:first-child { border-left: 3px solid var(--gold); }
    .row-group-borrowers td:first-child { border-left: 3px solid var(--sage); }
    .row-group-loans td:first-child { border-left: 3px solid #7b6cad; }

    /* ── RELATIONSHIPS ── */
    .rel-wrap {
      background: var(--card); border: 1px solid var(--border);
      border-radius: 10px; padding: 24px 28px; margin-bottom: 32px;
    }
    .rel-title { font-family: 'DM Serif Display', serif; font-size: 17px; margin-bottom: 18px; }
    .rel-list { display: flex; flex-direction: column; gap: 12px; }
    .rel-item {
      display: flex; align-items: center; gap: 14px;
      padding: 14px 18px; border: 1px solid var(--border);
      border-radius: 8px; background: var(--paper);
    }
    .rel-icon { font-size: 20px; flex-shrink: 0; }
    .rel-desc { font-size: 13.5px; line-height: 1.5; }
    .rel-desc strong { font-family: 'DM Mono', monospace; font-size: 12px; color: var(--accent); }
    .rel-type {
      margin-left: auto; flex-shrink: 0;
      font-family: 'DM Mono', monospace; font-size: 10px; letter-spacing: 1px;
      text-transform: uppercase; background: var(--cream);
      border: 1px solid var(--border); padding: 3px 10px; border-radius: 4px; color: var(--muted);
    }

    /* ── SQL SCHEMA ── */
    .sql-wrap {
      background: #111009; border-radius: 10px;
      padding: 24px 28px; margin-bottom: 32px;
      border: 1px solid rgba(245,240,232,0.06);
    }
    .sql-title {
      font-family: 'DM Mono', monospace; font-size: 11px; letter-spacing: 2px;
      text-transform: uppercase; color: var(--gold); margin-bottom: 18px;
    }
    .sql-wrap pre {
      font-family: 'DM Mono', monospace; font-size: 12.5px;
      line-height: 1.8; color: #c8d8c8; white-space: pre-wrap;
    }
    .sql-kw  { color: #c792ea; }
    .sql-fn  { color: #82aaff; }
    .sql-str { color: #c3e88d; }
    .sql-cmt { color: #4a6a4a; }
    .sql-tb  { color: var(--gold); font-weight: 600; }

    /* ── AUTHORS ── */
    .authors-card {
      background: var(--ink); border-radius: 10px;
      padding: 20px 24px;
      display: flex; align-items: center; justify-content: space-between;
    }
    .authors-label { font-family: 'DM Mono', monospace; font-size: 9px; letter-spacing: 2.5px; text-transform: uppercase; color: var(--muted); margin-bottom: 6px; }
    .authors-names { font-size: 14px; color: var(--paper); font-weight: 500; }
    .authors-course { font-family: 'DM Mono', monospace; font-size: 11px; color: var(--warm); margin-top: 3px; }
    .authors-right { text-align: right; }
    .authors-subject { font-family: 'DM Serif Display', serif; font-size: 18px; color: var(--gold); }
    .authors-lab { font-family: 'DM Mono', monospace; font-size: 10px; color: var(--muted); letter-spacing: 1px; margin-top: 3px; }
  </style>
</head>
<body>
<div id="app">
  <?php include 'includes/sidebar.php'; ?>

  <main class="main">
    <header class="topbar">
      <div class="page-title">Database Design</div>
      <div class="topbar-actions">
        <span style="font-family:'DM Mono',monospace;font-size:11px;color:var(--muted);letter-spacing:1px;">IT 56 · Laboratory Activity 2</span>
      </div>
    </header>

    <div class="content">

      <!-- Intro -->
      <div class="lab-badge">Central Mindanao University &nbsp;·&nbsp; <span>Laboratory 2</span> &nbsp;·&nbsp; IT 56</div>
      <div class="intro-card">
        <div class="intro-icon">🗄️</div>
        <div>
          <div class="intro-title">Database Design &amp; Data Modeling — ByteLibrary</div>
          <div class="intro-desc">
            This page documents the relational database structure designed to support the ByteLibrary system.
            It covers entity identification, field definitions, primary/foreign keys, relationships,
            an ERD diagram, and a complete form-to-database data mapping — as required by Laboratory Activity 2.
          </div>
        </div>
      </div>

      <!-- Table Structure -->
      <div class="section-header">
        <div class="section-title">Step 1–4 · Entities, Fields &amp; Primary Keys</div>
      </div>
      <div class="tables-grid">

        <!-- users -->
        <div class="db-table-card">
          <div class="db-table-header users">
            <span class="db-table-icon">👤</span>
            <span class="db-table-name">users</span>
          </div>
          <div class="db-table-body">
            <div class="db-field">
              <span class="field-key pk">PK</span>
              <span class="field-name">id</span>
              <span class="field-type">INT</span>
              <span class="field-constraint">AUTO_INCREMENT</span>
            </div>
            <div class="db-field">
              <span class="field-name">display_name</span>
              <span class="field-type">VARCHAR(150)</span>
            </div>
            <div class="db-field">
              <span class="field-name">username</span>
              <span class="field-type">VARCHAR(80)</span>
              <span class="field-constraint">UNIQUE</span>
            </div>
            <div class="db-field">
              <span class="field-name">password</span>
              <span class="field-type">VARCHAR(255)</span>
            </div>
            <div class="db-field">
              <span class="field-name">role</span>
              <span class="field-type">ENUM</span>
            </div>
            <div class="db-field">
              <span class="field-name">created_at</span>
              <span class="field-type">TIMESTAMP</span>
            </div>
          </div>
        </div>

        <!-- books -->
        <div class="db-table-card">
          <div class="db-table-header books">
            <span class="db-table-icon">📚</span>
            <span class="db-table-name">books</span>
          </div>
          <div class="db-table-body">
            <div class="db-field">
              <span class="field-key pk">PK</span>
              <span class="field-name">id</span>
              <span class="field-type">INT</span>
              <span class="field-constraint">AUTO_INCREMENT</span>
            </div>
            <div class="db-field">
              <span class="field-name">isbn</span>
              <span class="field-type">VARCHAR(20)</span>
              <span class="field-constraint">UNIQUE</span>
            </div>
            <div class="db-field">
              <span class="field-name">title</span>
              <span class="field-type">VARCHAR(255)</span>
            </div>
            <div class="db-field">
              <span class="field-name">author</span>
              <span class="field-type">VARCHAR(150)</span>
            </div>
            <div class="db-field">
              <span class="field-name">genre</span>
              <span class="field-type">VARCHAR(80)</span>
            </div>
            <div class="db-field">
              <span class="field-name">publisher</span>
              <span class="field-type">VARCHAR(150)</span>
            </div>
            <div class="db-field">
              <span class="field-name">year_published</span>
              <span class="field-type">YEAR</span>
            </div>
            <div class="db-field">
              <span class="field-name">copies</span>
              <span class="field-type">INT</span>
            </div>
            <div class="db-field">
              <span class="field-name">description</span>
              <span class="field-type">TEXT</span>
            </div>
            <div class="db-field">
              <span class="field-name">status</span>
              <span class="field-type">ENUM</span>
            </div>
            <div class="db-field">
              <span class="field-name">created_at</span>
              <span class="field-type">TIMESTAMP</span>
            </div>
          </div>
        </div>

        <!-- borrowers -->
        <div class="db-table-card">
          <div class="db-table-header borrowers">
            <span class="db-table-icon">👥</span>
            <span class="db-table-name">borrowers</span>
          </div>
          <div class="db-table-body">
            <div class="db-field">
              <span class="field-key pk">PK</span>
              <span class="field-name">id</span>
              <span class="field-type">INT</span>
              <span class="field-constraint">AUTO_INCREMENT</span>
            </div>
            <div class="db-field">
              <span class="field-name">name</span>
              <span class="field-type">VARCHAR(150)</span>
            </div>
            <div class="db-field">
              <span class="field-name">email</span>
              <span class="field-type">VARCHAR(150)</span>
              <span class="field-constraint">UNIQUE</span>
            </div>
            <div class="db-field">
              <span class="field-name">phone</span>
              <span class="field-type">VARCHAR(30)</span>
            </div>
            <div class="db-field">
              <span class="field-name">status</span>
              <span class="field-type">ENUM</span>
            </div>
            <div class="db-field">
              <span class="field-name">created_at</span>
              <span class="field-type">TIMESTAMP</span>
            </div>
          </div>
        </div>

        <!-- loans -->
        <div class="db-table-card" style="grid-column: 1 / -1;">
          <div class="db-table-header loans">
            <span class="db-table-icon">📋</span>
            <span class="db-table-name">loans</span>
          </div>
          <div class="db-table-body" style="display:grid;grid-template-columns:repeat(3,1fr);">
            <div class="db-field">
              <span class="field-key pk">PK</span>
              <span class="field-name">id</span>
              <span class="field-type">INT</span>
              <span class="field-constraint">AUTO_INCREMENT</span>
            </div>
            <div class="db-field">
              <span class="field-key fk">FK</span>
              <span class="field-name">book_id</span>
              <span class="field-type">INT</span>
            </div>
            <div class="db-field">
              <span class="field-key fk">FK</span>
              <span class="field-name">borrower_id</span>
              <span class="field-type">INT</span>
            </div>
            <div class="db-field">
              <span class="field-name">issue_date</span>
              <span class="field-type">DATE</span>
            </div>
            <div class="db-field">
              <span class="field-name">due_date</span>
              <span class="field-type">DATE</span>
            </div>
            <div class="db-field">
              <span class="field-name">return_date</span>
              <span class="field-type">DATE NULL</span>
            </div>
            <div class="db-field">
              <span class="field-name">penalty</span>
              <span class="field-type">DECIMAL(8,2)</span>
            </div>
            <div class="db-field">
              <span class="field-name">status</span>
              <span class="field-type">ENUM</span>
            </div>
            <div class="db-field">
              <span class="field-name">created_at</span>
              <span class="field-type">TIMESTAMP</span>
            </div>
          </div>
        </div>

      </div><!-- /tables-grid -->

      <!-- ERD DIAGRAM -->
      <div class="erd-wrap">
        <div class="erd-title">Step 6 · Entity Relationship Diagram (ERD)</div>
        <div class="erd-svg-wrap">
          <svg viewBox="0 0 860 420" width="100%" xmlns="http://www.w3.org/2000/svg" style="max-width:860px;font-family:'DM Mono',monospace;">
            <defs>
              <marker id="arr" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                <path d="M0,0 L0,6 L8,3 z" fill="#7a7269"/>
              </marker>
            </defs>

            <!-- USERS table -->
            <rect x="20" y="30" width="180" height="220" rx="8" fill="#faf7f2" stroke="#d4cdc0" stroke-width="1.5"/>
            <rect x="20" y="30" width="180" height="36" rx="8" fill="#1a1714"/>
            <rect x="20" y="54" width="180" height="12" rx="0" fill="#1a1714"/>
            <text x="110" y="53" text-anchor="middle" font-size="12" fill="#f5f0e8" font-weight="600" letter-spacing="1">USERS</text>
            <line x1="20" y1="66" x2="200" y2="66" stroke="#d4cdc0" stroke-width="1"/>
            <!-- PK -->
            <rect x="28" y="72" width="22" height="14" rx="3" fill="#fef3e6"/>
            <text x="39" y="83" text-anchor="middle" font-size="9" fill="#b36b00" font-weight="700">PK</text>
            <text x="57" y="83" font-size="11" fill="#1a1714">id</text>
            <text x="158" y="83" text-anchor="end" font-size="10" fill="#7a7269">INT</text>
            <line x1="28" y1="90" x2="192" y2="90" stroke="#ede8db" stroke-width="1"/>
            <text x="28" y="103" font-size="11" fill="#1a1714">display_name</text>
            <text x="192" y="103" text-anchor="end" font-size="10" fill="#7a7269">VARCHAR</text>
            <line x1="28" y1="110" x2="192" y2="110" stroke="#ede8db" stroke-width="1"/>
            <text x="28" y="123" font-size="11" fill="#1a1714">username</text>
            <text x="192" y="123" text-anchor="end" font-size="10" fill="#7a7269">VARCHAR</text>
            <line x1="28" y1="130" x2="192" y2="130" stroke="#ede8db" stroke-width="1"/>
            <text x="28" y="143" font-size="11" fill="#1a1714">password</text>
            <text x="192" y="143" text-anchor="end" font-size="10" fill="#7a7269">VARCHAR</text>
            <line x1="28" y1="150" x2="192" y2="150" stroke="#ede8db" stroke-width="1"/>
            <text x="28" y="163" font-size="11" fill="#1a1714">role</text>
            <text x="192" y="163" text-anchor="end" font-size="10" fill="#7a7269">ENUM</text>
            <line x1="28" y1="170" x2="192" y2="170" stroke="#ede8db" stroke-width="1"/>
            <text x="28" y="183" font-size="11" fill="#1a1714">created_at</text>
            <text x="192" y="183" text-anchor="end" font-size="10" fill="#7a7269">TIMESTAMP</text>

            <!-- BOOKS table -->
            <rect x="330" y="30" width="200" height="268" rx="8" fill="#faf7f2" stroke="#d4cdc0" stroke-width="1.5"/>
            <rect x="330" y="30" width="200" height="36" rx="8" fill="#3d1e0a"/>
            <rect x="330" y="54" width="200" height="12" rx="0" fill="#3d1e0a"/>
            <text x="430" y="53" text-anchor="middle" font-size="12" fill="#f5f0e8" font-weight="600" letter-spacing="1">BOOKS</text>
            <line x1="330" y1="66" x2="530" y2="66" stroke="#d4cdc0" stroke-width="1"/>
            <rect x="338" y="72" width="22" height="14" rx="3" fill="#fef3e6"/>
            <text x="349" y="83" text-anchor="middle" font-size="9" fill="#b36b00" font-weight="700">PK</text>
            <text x="367" y="83" font-size="11" fill="#1a1714">id</text>
            <text x="522" y="83" text-anchor="end" font-size="10" fill="#7a7269">INT</text>
            <line x1="338" y1="90" x2="522" y2="90" stroke="#ede8db" stroke-width="1"/>
            <!-- rows -->
            <text x="338" y="103" font-size="11" fill="#1a1714">isbn</text><text x="522" y="103" text-anchor="end" font-size="10" fill="#7a7269">VARCHAR</text>
            <line x1="338" y1="110" x2="522" y2="110" stroke="#ede8db" stroke-width="1"/>
            <text x="338" y="123" font-size="11" fill="#1a1714">title</text><text x="522" y="123" text-anchor="end" font-size="10" fill="#7a7269">VARCHAR</text>
            <line x1="338" y1="130" x2="522" y2="130" stroke="#ede8db" stroke-width="1"/>
            <text x="338" y="143" font-size="11" fill="#1a1714">author</text><text x="522" y="143" text-anchor="end" font-size="10" fill="#7a7269">VARCHAR</text>
            <line x1="338" y1="150" x2="522" y2="150" stroke="#ede8db" stroke-width="1"/>
            <text x="338" y="163" font-size="11" fill="#1a1714">genre</text><text x="522" y="163" text-anchor="end" font-size="10" fill="#7a7269">VARCHAR</text>
            <line x1="338" y1="170" x2="522" y2="170" stroke="#ede8db" stroke-width="1"/>
            <text x="338" y="183" font-size="11" fill="#1a1714">publisher</text><text x="522" y="183" text-anchor="end" font-size="10" fill="#7a7269">VARCHAR</text>
            <line x1="338" y1="190" x2="522" y2="190" stroke="#ede8db" stroke-width="1"/>
            <text x="338" y="203" font-size="11" fill="#1a1714">year_published</text><text x="522" y="203" text-anchor="end" font-size="10" fill="#7a7269">YEAR</text>
            <line x1="338" y1="210" x2="522" y2="210" stroke="#ede8db" stroke-width="1"/>
            <text x="338" y="223" font-size="11" fill="#1a1714">copies</text><text x="522" y="223" text-anchor="end" font-size="10" fill="#7a7269">INT</text>
            <line x1="338" y1="230" x2="522" y2="230" stroke="#ede8db" stroke-width="1"/>
            <text x="338" y="243" font-size="11" fill="#1a1714">description</text><text x="522" y="243" text-anchor="end" font-size="10" fill="#7a7269">TEXT</text>
            <line x1="338" y1="250" x2="522" y2="250" stroke="#ede8db" stroke-width="1"/>
            <text x="338" y="263" font-size="11" fill="#1a1714">status</text><text x="522" y="263" text-anchor="end" font-size="10" fill="#7a7269">ENUM</text>
            <line x1="338" y1="270" x2="522" y2="270" stroke="#ede8db" stroke-width="1"/>
            <text x="338" y="283" font-size="11" fill="#1a1714">created_at</text><text x="522" y="283" text-anchor="end" font-size="10" fill="#7a7269">TIMESTAMP</text>

            <!-- BORROWERS table -->
            <rect x="640" y="30" width="200" height="208" rx="8" fill="#faf7f2" stroke="#d4cdc0" stroke-width="1.5"/>
            <rect x="640" y="30" width="200" height="36" rx="8" fill="#0c1f18"/>
            <rect x="640" y="54" width="200" height="12" rx="0" fill="#0c1f18"/>
            <text x="740" y="53" text-anchor="middle" font-size="12" fill="#f5f0e8" font-weight="600" letter-spacing="1">BORROWERS</text>
            <line x1="640" y1="66" x2="840" y2="66" stroke="#d4cdc0" stroke-width="1"/>
            <rect x="648" y="72" width="22" height="14" rx="3" fill="#fef3e6"/>
            <text x="659" y="83" text-anchor="middle" font-size="9" fill="#b36b00" font-weight="700">PK</text>
            <text x="677" y="83" font-size="11" fill="#1a1714">id</text>
            <text x="832" y="83" text-anchor="end" font-size="10" fill="#7a7269">INT</text>
            <line x1="648" y1="90" x2="832" y2="90" stroke="#ede8db" stroke-width="1"/>
            <text x="648" y="103" font-size="11" fill="#1a1714">name</text><text x="832" y="103" text-anchor="end" font-size="10" fill="#7a7269">VARCHAR</text>
            <line x1="648" y1="110" x2="832" y2="110" stroke="#ede8db" stroke-width="1"/>
            <text x="648" y="123" font-size="11" fill="#1a1714">email</text><text x="832" y="123" text-anchor="end" font-size="10" fill="#7a7269">VARCHAR</text>
            <line x1="648" y1="130" x2="832" y2="130" stroke="#ede8db" stroke-width="1"/>
            <text x="648" y="143" font-size="11" fill="#1a1714">phone</text><text x="832" y="143" text-anchor="end" font-size="10" fill="#7a7269">VARCHAR</text>
            <line x1="648" y1="150" x2="832" y2="150" stroke="#ede8db" stroke-width="1"/>
            <text x="648" y="163" font-size="11" fill="#1a1714">status</text><text x="832" y="163" text-anchor="end" font-size="10" fill="#7a7269">ENUM</text>
            <line x1="648" y1="170" x2="832" y2="170" stroke="#ede8db" stroke-width="1"/>
            <text x="648" y="183" font-size="11" fill="#1a1714">created_at</text><text x="832" y="183" text-anchor="end" font-size="10" fill="#7a7269">TIMESTAMP</text>

            <!-- LOANS table -->
            <rect x="330" y="340" width="200" height="62" rx="8" fill="#faf7f2" stroke="#d4cdc0" stroke-width="1.5"/>
            <rect x="330" y="340" width="200" height="28" rx="8" fill="#1a0f28"/>
            <rect x="330" y="356" width="200" height="12" rx="0" fill="#1a0f28"/>
            <text x="430" y="359" text-anchor="middle" font-size="12" fill="#f5f0e8" font-weight="600" letter-spacing="1">LOANS</text>
            <line x1="330" y1="368" x2="530" y2="368" stroke="#d4cdc0" stroke-width="1"/>
            <rect x="338" y="374" width="22" height="14" rx="3" fill="#fef3e6"/>
            <text x="349" y="385" text-anchor="middle" font-size="9" fill="#b36b00" font-weight="700">PK</text>
            <text x="367" y="385" font-size="11" fill="#1a1714">id</text>
            <rect x="393" y="374" width="22" height="14" rx="3" fill="#e6eef8"/>
            <text x="404" y="385" text-anchor="middle" font-size="9" fill="#2a5ba0" font-weight="700">FK</text>
            <text x="418" y="385" font-size="11" fill="#1a1714">book_id</text>
            <rect x="471" y="374" width="22" height="14" rx="3" fill="#e6eef8"/>
            <text x="482" y="385" text-anchor="middle" font-size="9" fill="#2a5ba0" font-weight="700">FK</text>
            <text x="496" y="385" font-size="11" fill="#1a1714">borrower_id</text>

            <!-- Relationship lines -->
            <!-- BOOKS.id → LOANS.book_id -->
            <line x1="430" y1="298" x2="430" y2="340" stroke="#7a7269" stroke-width="1.5" stroke-dasharray="4,3" marker-end="url(#arr)"/>
            <text x="434" y="323" font-size="10" fill="#7a7269">1 : N</text>

            <!-- BORROWERS.id → LOANS.borrower_id -->
            <line x1="740" y1="238" x2="740" y2="371" stroke="#7a7269" stroke-width="1.5" stroke-dasharray="4,3"/>
            <line x1="740" y1="371" x2="530" y2="384" stroke="#7a7269" stroke-width="1.5" stroke-dasharray="4,3" marker-end="url(#arr)"/>
            <text x="650" y="365" font-size="10" fill="#7a7269">1 : N</text>
          </svg>
        </div>
      </div>

      <!-- RELATIONSHIPS -->
      <div class="rel-wrap">
        <div class="rel-title">Step 5 · Relationships</div>
        <div class="rel-list">
          <div class="rel-item">
            <div class="rel-icon">📚</div>
            <div class="rel-desc">
              <strong>books</strong> → <strong>loans</strong><br>
              One book can have many loan records over time. The <strong>book_id</strong> in the loans table references <strong>books.id</strong>.
            </div>
            <div class="rel-type">One-to-Many</div>
          </div>
          <div class="rel-item">
            <div class="rel-icon">👥</div>
            <div class="rel-desc">
              <strong>borrowers</strong> → <strong>loans</strong><br>
              One borrower can have many loan records. The <strong>borrower_id</strong> in loans references <strong>borrowers.id</strong>.
            </div>
            <div class="rel-type">One-to-Many</div>
          </div>
          <div class="rel-item">
            <div class="rel-icon">👤</div>
            <div class="rel-desc">
              <strong>users</strong> (staff/admin) are independent from borrowers. Users log in to manage the system; borrowers are library members who borrow books.
            </div>
            <div class="rel-type">Independent</div>
          </div>
        </div>
      </div>

      <!-- DATA MAPPING -->
      <div class="mapping-wrap">
        <div class="mapping-header">
          <div>
            <div class="mapping-header-title">Form Analysis &amp; Data Mapping</div>
          </div>
          <div class="mapping-header-sub">Page → Field → Data Type → Table</div>
        </div>
        <table class="mapping-table">
          <thead>
            <tr>
              <th>Page</th>
              <th>Form Field / Label</th>
              <th>Input Type</th>
              <th>Data Type</th>
              <th>DB Table</th>
              <th>Column Name</th>
            </tr>
          </thead>
          <tbody>
            <!-- Register -->
            <tr class="row-group">
              <td class="page-col" rowspan="4">Register</td>
              <td class="field-col">Display Name</td><td>text</td><td class="type-col">VARCHAR(150)</td>
              <td class="table-col">users</td><td class="field-col">display_name</td>
            </tr>
            <tr class="row-group">
              <td class="field-col">Username</td><td>text</td><td class="type-col">VARCHAR(80)</td>
              <td class="table-col">users</td><td class="field-col">username</td>
            </tr>
            <tr class="row-group">
              <td class="field-col">Password</td><td>password</td><td class="type-col">VARCHAR(255)</td>
              <td class="table-col">users</td><td class="field-col">password</td>
            </tr>
            <tr class="row-group">
              <td class="field-col">Confirm Password</td><td>password</td><td class="type-col">VARCHAR(255)</td>
              <td class="table-col">users</td><td class="field-col">(validation only)</td>
            </tr>
            <!-- Login -->
            <tr class="row-group" style="border-top:2px solid var(--border);">
              <td class="page-col" rowspan="2">Login</td>
              <td class="field-col">Username</td><td>text</td><td class="type-col">VARCHAR(80)</td>
              <td class="table-col">users</td><td class="field-col">username</td>
            </tr>
            <tr class="row-group">
              <td class="field-col">Password</td><td>password</td><td class="type-col">VARCHAR(255)</td>
              <td class="table-col">users</td><td class="field-col">password</td>
            </tr>
            <!-- Add Book -->
            <tr class="row-group-books" style="border-top:2px solid var(--border);">
              <td class="page-col" rowspan="9">Add Book</td>
              <td class="field-col">ISBN</td><td>text</td><td class="type-col">VARCHAR(20)</td>
              <td class="table-col">books</td><td class="field-col">isbn</td>
            </tr>
            <tr class="row-group-books">
              <td class="field-col">Book Title</td><td>text</td><td class="type-col">VARCHAR(255)</td>
              <td class="table-col">books</td><td class="field-col">title</td>
            </tr>
            <tr class="row-group-books">
              <td class="field-col">Author</td><td>text</td><td class="type-col">VARCHAR(150)</td>
              <td class="table-col">books</td><td class="field-col">author</td>
            </tr>
            <tr class="row-group-books">
              <td class="field-col">Genre</td><td>select</td><td class="type-col">VARCHAR(80)</td>
              <td class="table-col">books</td><td class="field-col">genre</td>
            </tr>
            <tr class="row-group-books">
              <td class="field-col">Publisher</td><td>text</td><td class="type-col">VARCHAR(150)</td>
              <td class="table-col">books</td><td class="field-col">publisher</td>
            </tr>
            <tr class="row-group-books">
              <td class="field-col">Year Published</td><td>number</td><td class="type-col">YEAR</td>
              <td class="table-col">books</td><td class="field-col">year_published</td>
            </tr>
            <tr class="row-group-books">
              <td class="field-col">Copies</td><td>number</td><td class="type-col">INT</td>
              <td class="table-col">books</td><td class="field-col">copies</td>
            </tr>
            <tr class="row-group-books">
              <td class="field-col">Description</td><td>textarea</td><td class="type-col">TEXT</td>
              <td class="table-col">books</td><td class="field-col">description</td>
            </tr>
            <tr class="row-group-books">
              <td class="field-col">Status</td><td>(auto)</td><td class="type-col">ENUM</td>
              <td class="table-col">books</td><td class="field-col">status</td>
            </tr>
            <!-- Borrowers -->
            <tr class="row-group-borrowers" style="border-top:2px solid var(--border);">
              <td class="page-col" rowspan="4">Borrowers</td>
              <td class="field-col">Full Name</td><td>text</td><td class="type-col">VARCHAR(150)</td>
              <td class="table-col">borrowers</td><td class="field-col">name</td>
            </tr>
            <tr class="row-group-borrowers">
              <td class="field-col">Email Address</td><td>email</td><td class="type-col">VARCHAR(150)</td>
              <td class="table-col">borrowers</td><td class="field-col">email</td>
            </tr>
            <tr class="row-group-borrowers">
              <td class="field-col">Phone Number</td><td>text</td><td class="type-col">VARCHAR(30)</td>
              <td class="table-col">borrowers</td><td class="field-col">phone</td>
            </tr>
            <tr class="row-group-borrowers">
              <td class="field-col">Status</td><td>(auto)</td><td class="type-col">ENUM</td>
              <td class="table-col">borrowers</td><td class="field-col">status</td>
            </tr>
            <!-- Loans / Status -->
            <tr class="row-group-loans" style="border-top:2px solid var(--border);">
              <td class="page-col" rowspan="5">Loan Status</td>
              <td class="field-col">Book (select)</td><td>select</td><td class="type-col">INT</td>
              <td class="table-col">loans</td><td class="field-col">book_id</td>
            </tr>
            <tr class="row-group-loans">
              <td class="field-col">Borrower (select)</td><td>select</td><td class="type-col">INT</td>
              <td class="table-col">loans</td><td class="field-col">borrower_id</td>
            </tr>
            <tr class="row-group-loans">
              <td class="field-col">Issue Date</td><td>date</td><td class="type-col">DATE</td>
              <td class="table-col">loans</td><td class="field-col">issue_date</td>
            </tr>
            <tr class="row-group-loans">
              <td class="field-col">Due Date</td><td>date</td><td class="type-col">DATE</td>
              <td class="table-col">loans</td><td class="field-col">due_date</td>
            </tr>
            <tr class="row-group-loans">
              <td class="field-col">Penalty</td><td>display</td><td class="type-col">DECIMAL(8,2)</td>
              <td class="table-col">loans</td><td class="field-col">penalty</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- SQL SCHEMA -->
      <div class="sql-wrap">
        <div class="sql-title">⬡ MySQL Schema — ByteLibrary Database</div>
        <pre><span class="sql-cmt">-- Step 7: Convert to Table Structure</span>
<span class="sql-kw">CREATE DATABASE</span> bytelibrary_db;
<span class="sql-kw">USE</span> bytelibrary_db;

<span class="sql-cmt">-- Table 1: users (library staff accounts)</span>
<span class="sql-kw">CREATE TABLE</span> <span class="sql-tb">users</span> (
  id            <span class="sql-fn">INT AUTO_INCREMENT PRIMARY KEY</span>,
  display_name  <span class="sql-fn">VARCHAR</span>(150) NOT NULL,
  username      <span class="sql-fn">VARCHAR</span>(80) UNIQUE NOT NULL,
  password      <span class="sql-fn">VARCHAR</span>(255) NOT NULL,
  role          <span class="sql-fn">ENUM</span>(<span class="sql-str">'Librarian'</span>, <span class="sql-str">'Admin'</span>) DEFAULT <span class="sql-str">'Librarian'</span>,
  created_at    <span class="sql-fn">TIMESTAMP DEFAULT CURRENT_TIMESTAMP</span>
);

<span class="sql-cmt">-- Table 2: books</span>
<span class="sql-kw">CREATE TABLE</span> <span class="sql-tb">books</span> (
  id             <span class="sql-fn">INT AUTO_INCREMENT PRIMARY KEY</span>,
  isbn           <span class="sql-fn">VARCHAR</span>(20) UNIQUE,
  title          <span class="sql-fn">VARCHAR</span>(255) NOT NULL,
  author         <span class="sql-fn">VARCHAR</span>(150),
  genre          <span class="sql-fn">VARCHAR</span>(80),
  publisher      <span class="sql-fn">VARCHAR</span>(150),
  year_published <span class="sql-fn">YEAR</span>,
  copies         <span class="sql-fn">INT DEFAULT</span> 1,
  description    <span class="sql-fn">TEXT</span>,
  status         <span class="sql-fn">ENUM</span>(<span class="sql-str">'Available'</span>, <span class="sql-str">'Borrowed'</span>, <span class="sql-str">'Overdue'</span>) DEFAULT <span class="sql-str">'Available'</span>,
  created_at     <span class="sql-fn">TIMESTAMP DEFAULT CURRENT_TIMESTAMP</span>
);

<span class="sql-cmt">-- Table 3: borrowers (library members)</span>
<span class="sql-kw">CREATE TABLE</span> <span class="sql-tb">borrowers</span> (
  id          <span class="sql-fn">INT AUTO_INCREMENT PRIMARY KEY</span>,
  name        <span class="sql-fn">VARCHAR</span>(150) NOT NULL,
  email       <span class="sql-fn">VARCHAR</span>(150) UNIQUE,
  phone       <span class="sql-fn">VARCHAR</span>(30),
  status      <span class="sql-fn">ENUM</span>(<span class="sql-str">'Active'</span>, <span class="sql-str">'Suspended'</span>) DEFAULT <span class="sql-str">'Active'</span>,
  created_at  <span class="sql-fn">TIMESTAMP DEFAULT CURRENT_TIMESTAMP</span>
);

<span class="sql-cmt">-- Table 4: loans (junction — books ↔ borrowers)</span>
<span class="sql-kw">CREATE TABLE</span> <span class="sql-tb">loans</span> (
  id           <span class="sql-fn">INT AUTO_INCREMENT PRIMARY KEY</span>,
  book_id      <span class="sql-fn">INT NOT NULL</span>,
  borrower_id  <span class="sql-fn">INT NOT NULL</span>,
  issue_date   <span class="sql-fn">DATE NOT NULL</span>,
  due_date     <span class="sql-fn">DATE NOT NULL</span>,
  return_date  <span class="sql-fn">DATE NULL</span>,
  penalty      <span class="sql-fn">DECIMAL</span>(8,2) DEFAULT 0.00,
  status       <span class="sql-fn">ENUM</span>(<span class="sql-str">'Active'</span>, <span class="sql-str">'Returned'</span>, <span class="sql-str">'Overdue'</span>) DEFAULT <span class="sql-str">'Active'</span>,
  created_at   <span class="sql-fn">TIMESTAMP DEFAULT CURRENT_TIMESTAMP</span>,
  <span class="sql-kw">FOREIGN KEY</span> (book_id)     <span class="sql-kw">REFERENCES</span> <span class="sql-tb">books</span>(id),
  <span class="sql-kw">FOREIGN KEY</span> (borrower_id) <span class="sql-kw">REFERENCES</span> <span class="sql-tb">borrowers</span>(id)
);</pre>
      </div>

      <!-- Authors footer -->
      <div class="authors-card">
        <div>
          <div class="authors-label">Group Members</div>
          <div class="authors-names">Cadigal, Sciro Elric A. &nbsp;·&nbsp; Castañares, Katrina D.</div>
          <div class="authors-course">BSIT 2D &nbsp;·&nbsp; Central Mindanao University</div>
        </div>
        <div class="authors-right">
          <div class="authors-subject">IT 56</div>
          <div class="authors-lab">Laboratory Activity 2 &nbsp;·&nbsp; 2025</div>
        </div>
      </div>

    </div><!-- /content -->
  </main>
</div>
</body>
</html>
