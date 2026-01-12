<?php 
include 'navbar.php';
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    :root{
        --dark: #850E35;
        --pink: #EE6983;
        --cream: #FCF5EE;
        --soft: #FFC4C4;
        --glass: rgba(252, 245, 238, 0.18);
        --glass2: rgba(252, 245, 238, 0.30);
        --shadow: 0 18px 55px rgba(133, 14, 53, 0.28);
    }

    body{
        font-family: "Poppins", sans-serif;
    }

    /* page wrapper */
    .about-page{
        max-width: 1150px;
        margin: 0 auto;
        padding: 35px 18px 70px;
        position: relative;
        z-index: 1;
    }

    /* cute floating blobs */
    .about-page::before,
    .about-page::after{
        content:"";
        position:absolute;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        filter: blur(35px);
        opacity: 0.35;
        z-index:-1;
    }
    .about-page::before{
        background: radial-gradient(circle, var(--pink), transparent 60%);
        top: -70px;
        left: -60px;
    }
    .about-page::after{
        background: radial-gradient(circle, var(--soft), transparent 60%);
        bottom: -90px;
        right: -80px;
    }

    /* hero */
    .about-hero{
        background: linear-gradient(135deg, rgba(238,105,131,0.18), rgba(133,14,53,0.18));
        border: 1px solid rgba(252,245,238,0.30);
        backdrop-filter: blur(10px);
        border-radius: 28px;
        padding: 34px 26px;
        box-shadow: var(--shadow);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .about-hero .badge{
        display: inline-flex;
        gap: 10px;
        align-items: center;
        background: rgba(252,245,238,0.35);
        color: var(--dark);
        border: 1px solid rgba(238,105,131,0.25);
        padding: 10px 16px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 14px;
    }

    .about-title{
        font-size: 44px;
        font-weight: 800;
        line-height: 1.2;
        margin: 6px 0 10px;
        background: linear-gradient(135deg, var(--pink), var(--dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: 1px;
    }

    .about-subtitle{
        max-width: 900px;
        margin: 0 auto;
        color: var(--cream);
        font-size: 17px;
        font-weight: 500;
        text-shadow: 2px 2px 10px rgba(133, 14, 53, 0.65);
        opacity: 0.95;
    }

    /* layout grid */
    .about-grid{
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
        margin-top: 22px;
    }

    .cardx{
        background: rgba(252,245,238,0.88);
        border-radius: 26px;
        padding: 26px 26px;
        box-shadow: var(--shadow);
        border: 1px solid rgba(133,14,53,0.12);
        position: relative;
        overflow: hidden;
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .cardx::before{
        content:"";
        position:absolute;
        inset: -60px -80px auto auto;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(238,105,131,0.35), transparent 60%);
        border-radius: 50%;
        filter: blur(15px);
    }

    .cardx:hover{
        transform: translateY(-6px);
        box-shadow: 0 22px 65px rgba(133, 14, 53, 0.33);
    }

    .cardx h3{
        color: var(--dark);
        font-weight: 800;
        font-size: 22px;
        margin-bottom: 14px;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    /* feature chips */
    .chips{
        display:flex;
        flex-wrap:wrap;
        gap: 10px;
        margin-top: 14px;
    }
    .chip{
        background: linear-gradient(135deg, rgba(238,105,131,0.18), rgba(133,14,53,0.08));
        border: 1px solid rgba(238,105,131,0.28);
        color: var(--dark);
        border-radius: 999px;
        padding: 10px 14px;
        font-weight: 700;
        font-size: 13px;
        display:flex;
        gap: 8px;
        align-items:center;
    }

    /* database pill list */
    .pill-list{
        display:grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 14px;
    }
    .pill{
        background: rgba(252,245,238,0.70);
        border: 1px solid rgba(133,14,53,0.12);
        border-left: 5px solid var(--pink);
        border-radius: 14px;
        padding: 10px 12px;
        font-size: 14px;
        color: #5a0b25;
        font-weight: 600;
    }
    .pill strong{
        color: var(--dark);
        font-weight: 800;
    }

    .note{
        margin-top: 14px;
        color: #5a0b25;
        font-weight: 600;
        line-height: 1.8;
        background: rgba(255,196,196,0.25);
        border: 1px solid rgba(238,105,131,0.25);
        padding: 14px 16px;
        border-radius: 18px;
    }

    /* developers section */
    .dev-section{
        margin-top: 22px;
        padding: 26px;
        border-radius: 28px;
        background: linear-gradient(135deg, rgba(252,245,238,0.92), rgba(252,245,238,0.82));
        box-shadow: var(--shadow);
        border: 1px solid rgba(133,14,53,0.12);
        position: relative;
        overflow:hidden;
    }

    .dev-section h2{
        margin: 0 0 18px;
        color: var(--dark);
        font-size: 28px;
        font-weight: 900;
        display:flex;
        gap: 10px;
        align-items:center;
    }

    .dev-grid{
        display:grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .dev-card{
        background: linear-gradient(135deg, rgba(255,196,196,0.35), rgba(238,105,131,0.12));
        border: 1px solid rgba(238,105,131,0.30);
        border-radius: 22px;
        padding: 18px 18px;
        display:flex;
        gap: 14px;
        align-items:center;
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .dev-card:hover{
        transform: translateY(-6px);
        box-shadow: 0 18px 40px rgba(133, 14, 53, 0.22);
    }

    .avatar{
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--pink), var(--dark));
        display:flex;
        align-items:center;
        justify-content:center;
        color: var(--cream);
        font-weight: 900;
        letter-spacing: 1px;
        box-shadow: 0 10px 22px rgba(133, 14, 53, 0.25);
        flex-shrink: 0;
    }

    .dev-name{
        margin: 0;
        font-size: 18px;
        font-weight: 900;
        color: var(--dark);
    }

    .dev-info{
        margin: 4px 0 0;
        color: #5a0b25;
        font-weight: 650;
        line-height: 1.7;
        font-size: 14px;
    }

    .email{
        color: var(--pink);
        font-weight: 900;
    }

    /* responsive */
    @media (max-width: 900px){
        .about-grid{ grid-template-columns: 1fr; }
        .dev-grid{ grid-template-columns: 1fr; }
        .about-title{ font-size: 34px; }
    }
</style>

<div class="about-page">

    <div class="about-hero">
        <div class="badge">📖 Library Project • Modern UI • Role Based</div>
        <div class="about-title">About the Library Management System</div>
        <p class="about-subtitle">
            This Library Management System helps administrators and staff efficiently manage books, authors, publishers, borrowers, loans, and sales. 
            It provides a clean interface, powerful features, and role-based access control.
        </p>
    </div>

    <div class="about-grid">

        <!-- Features -->
        <div class="cardx">
            <h3>⭐ System Features</h3>

            <div class="chips">
                <div class="chip">🔐 Secure login + hashing</div>
                <div class="chip">👤 Roles (Admin/Staff/Student)</div>
                <div class="chip">🛠 Full CRUD modules</div>
                <div class="chip">📅 Loans + due dates</div>
                <div class="chip">💳 Sales + auto availability</div>
                <div class="chip">🔎 Search & filtering</div>
                <div class="chip">📊 10+ SQL reports</div>
                <div class="chip">📱 Responsive UI</div>
            </div>
        </div>

        <!-- Database -->
        <div class="cardx">
            <h3>📚 Database Overview</h3>
            <p style="color:#5a0b25; font-weight:650; line-height:1.8; margin:0 0 10px;">
                The system uses a structured SQL database designed for efficient library operations. Key tables include:
            </p>

            <div class="pill-list">
                <div class="pill"><strong>book</strong> — title, category, price, publisher</div>
                <div class="pill"><strong>author</strong> — details & biography</div>
                <div class="pill"><strong>bookauthor</strong> — many-to-many relationship</div>
                <div class="pill"><strong>publisher</strong> — publisher information</div>
                <div class="pill"><strong>borrower</strong> — library members</div>
                <div class="pill"><strong>loan</strong> — borrowing transactions</div>
                <div class="pill"><strong>sale</strong> — book sales</div>
                <div class="pill"><strong>users</strong> — system accounts</div>
            </div>

            <div class="note">
                ✅ Relational integrity is maintained with PK/FK constraints and triggers — for example, when a sale occurs, 
                the system automatically updates book availability.
            </div>
        </div>

    </div>

    <!-- Developers -->
    <div class="dev-section">
        <h2>💻 Developers</h2>

        <div class="dev-grid">
            <div class="dev-card">
                <div class="avatar">SA</div>
                <div>
                    <p class="dev-name">Salma Abu Odeh</p>
                    <p class="dev-info">
                        📚 Bethlehem University <br>
                        🎓 Year 3, Software Engineering <br>
                        ✉️ <span class="email">202302878@bethlehem.edu</span>
                    </p>
                </div>
            </div>

            <div class="dev-card">
                <div class="avatar">JK</div>
                <div>
                    <p class="dev-name">Jiovanni Kitlo</p>
                    <p class="dev-info">
                        📚 Bethlehem University <br>
                        🎓 Year 3, Software Engineering <br>
                        ✉️ <span class="email">202404659@bethlehem.edu</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
